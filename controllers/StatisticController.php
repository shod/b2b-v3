<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class StatisticController extends Controller
{
    public $seller_id;
    var $offset = 250;

    /**
     * @inheritdoc
     *
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $this->seller_id = Yii::$app->user->identity->getId();
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionGetAnalysis()
    {
        $pid = Yii::$app->request->get('pid');
        $json["header"] = 'Развернутая аналитика по выбранному товару';


        //$sids = $whirl->parms->get('sids');
        //$sql_sids = $sids ? " and ps.seller_id not in ({$sids})" : "";
        $sql = "SELECT ps.title, ps.seller_id, ROUND(ip.min_cost_by / 10000, 2) AS seller_cost_by from product_seller as ps 
							join index_product_cost_data as ip on (ps.product_id = ip.product_id and ps.seller_id = ip.seller_id)
							WHERE ip.product_id = {$pid} and ps.active = 1 GROUP BY ip.seller_id ORDER BY ip.min_cost_by";
        $data = \Yii::$app->db->createCommand($sql)->queryAll();
        $html = "<table class='table'><tr><th>Название от продавца</th><th>Цена</th></tr>";
        foreach ((array)$data as $d) {
            $cl = ($this->seller_id == $d['seller_id']) ? "tr_primary" : "";
            $html .= "<tr class='{$cl}'>
									<td>{$d['title']}</td>
									<td><b>{$d['seller_cost_by']}</b> руб.</td>
								</tr>";
        }
        $html .= "</table>";
        $json["body"] = $html;
        echo Json::encode($json);
    }

    public function actionCostAnalysisCsv(){
        $sids = Yii::$app->request->get('sids');
        $sql_sids = $sids ? " and idx.seller_id not in ({$sids})" : "";
        $this->export_csv($sql_sids);
        exit;
    }


    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionMonth()
    {
        return $this->render('month');
    }

    public function actionCostAnalysis()
    {
        $vars = [];
        Yii::$app->view->params['customParam'] = 'Аналитика цен поможет вам сделать свои предложения более привлекательными для покупателей.';
        $bit = \Yii::$app->db->createCommand("select f_is_setting_bit_set(setting_bit,'seller_b2b','analyze') as is_set from seller as t where t.id={$this->seller_id}")->queryOne();
        if ($bit && !$bit['is_set']) {
            $template = "no-payd-analysis";
        } else {
            $page = Yii::$app->request->get("page") ? Yii::$app->request->get("page")-1 : 0;
            $offset = $page * $this->offset;
            $template = "cost-analysis";

            $section_id = Yii::$app->request->get("section_id");
            $brand = Yii::$app->request->get("brand");
            $basic_name = Yii::$app->request->get("basic_name");
            $wh_state = Yii::$app->request->get("wh_state");
            $sids = Yii::$app->request->get("sids");

            $sql_section = $section_id ? " and ip.index_section_id = {$section_id}" : "";
            $sql_brand = $brand ? " and ip.brand_value = '{$brand}'" : "";
            $sql_name = $basic_name ? " and ip.basic_name like '%{$basic_name}%'" : "";
            $sql_wh_state = $wh_state ? " and ps.wh_state = {$wh_state}" : "";
            $sql_sids = $sids ? " and idx.seller_id not in ({$sids})" : "";

            $vars['wh_state_'.$wh_state] = "selected";
            $vars['sids'] = $sids;

            $data = \Yii::$app->db->createCommand("select ip.product_id, ip.basic_name, ROUND(ps.cost_by/10000,2) as seller_cost_by
									, ROUND(min(min_cost_by)/10000,2) as min_cost, ROUND(max(max_cost_by)/10000,2) as max_cost
									, sum(idx.cnt) as cnt_cost, ip.catalog_name, vcs.catalog_id
									from index_product_cost_data as idx, product_seller as ps, index_product as ip, v_catalog_sections as vcs
									where ps.seller_id = {$this->seller_id}
									and idx.product_id = ps.product_id
									and ip.product_id = ps.product_id and vcs.section_id = ip.index_section_id and idx.flag = 0  {$sql_section} {$sql_brand} {$sql_name} {$sql_wh_state} {$sql_sids}
									GROUP BY idx.product_id limit {$offset}, 250")->queryAll();
            $cnt = 0;
            $cnt_min = 0;
            $cnt_max = 0;
            $vars['data'] = "";
            foreach ((array)$data as $r) {
                $cnt++;
                if (floatval($r['min_cost']) != floatval($r['max_cost'])) {
                    if (floatval($r['min_cost']) >= floatval($r['seller_cost_by'])) {
                        $r['class_success'] = "tr_success";
                        $cnt_min++;
                    }

                    if (floatval($r['max_cost']) <= floatval($r['seller_cost_by'])) {
                        $r['class_danger'] = "tr_danger";
                        $cnt_max++;
                    }
                }


                $vars['data'] .= $this->renderPartial('tmpl/analysis-item', $r);
            }
            $vars['min'] = round($cnt_min / $cnt * 100, 2);
            $vars['max'] = round($cnt_max / $cnt * 100, 2);
            $vars['pages'] = $this->get_pages($section_id, $brand, $basic_name, $wh_state, $page);


            $sections = \Yii::$app->db->createCommand("SELECT ip.index_section_id as id, ip.catalog_name as name from index_product as ip
												join product_seller as ps on (ps.product_id = ip.product_id)
												WHERE ps.seller_id = {$this->seller_id} GROUP BY ip.catalog_name;")->queryAll();
            $vars['sections'] = '';
            foreach((array)$sections as $s)
            {
                $selected = ($s['id'] == $section_id) ? "selected" : "";
                $vars['sections'] .= "<option value='{$s['id']}' {$selected}>{$s['name']}</option>";
            }
            $vars['brands'] = '';
            $brands = \Yii::$app->db->createCommand("SELECT ip.brand_id, ip.brand_value as brand from index_product as ip
											join product_seller as ps on (ps.product_id = ip.product_id)
											WHERE ps.seller_id = {$this->seller_id} and ip.brand_id is not null GROUP BY brand_value")->queryAll();
            foreach((array)$brands as $b)
            {
                $selected = $b['brand'] == $brand ? "selected" : "";
                $vars['brands'] .= "<option value='{$b['brand']}' {$selected} >{$b['brand']}</option>";
            }

        }

        return $this->render($template, $vars);
    }

    private function get_pages($section_id, $brand, $basic_name, $wh_state, $page=0){
        $sql_section = $section_id ? " and ip.index_section_id = {$section_id}" : "";
        $sql_brand = $brand ? " and ip.brand_value = '{$brand}'" : "";
        $sql_name = $basic_name ? " and ip.basic_name like '%{$basic_name}%'" : "";
        $sql_wh_state = $wh_state ? " and ps.wh_state = {$wh_state}" : "";
        $cnt =  \Yii::$app->db->createCommand("select count(DISTINCT idx.product_id) as cnt
									from index_product_cost_data as idx, product_seller as ps, index_product as ip, v_catalog_sections as vcs
									where ps.seller_id = {$this->seller_id}
									and idx.product_id = ps.product_id
									and ip.product_id = ps.product_id and vcs.section_id = ip.index_section_id {$sql_section} {$sql_brand} {$sql_name} {$sql_wh_state}")->queryOne();
        $count = $cnt['cnt'];
        $page_all = ceil($count / $this->offset);
        $first = 1;
        $last = $page_all;
        $pages = '';

        $url = "/statistic/cost-analysis/?";
        if (($page > 1) && ($last >= 8)){
            $pages .= "<li class='page-item'><a class=\"page-link\" href=\"{$url}\" >1</a></li>";
            if($page > 2){
                $pages .= "<li class='page-item'><a class=\"page-link\" >...</a></li>";
            }
        }

        for ($p = $first; $p < $last + 1; $p++)
        {
            if ($last < 8) {
                $a_selected = ($p == ($page + 1)) ? "active" : "";
                $pages .= "<li class='page-item {$a_selected}'><a class=\"page-link\" href=\"{$url}&page={$p}\" >{$p}</a></li>";
            } else {

                if (($p == $page+1) || ($p==$page) || ($p == $page + 2))
                {
                    $a_selected = ($p == ($page + 1)) ? "active" : "";
                    $pages .= "<li class='page-item {$a_selected}'><a class=\"page-link\" href=\"{$url}&page={$p}\" >{$p}</a></li>";
                }
            }
        }
        if (((($page < $last + 1) || ($page < $last)) && ($last >= 8))){
            $pages .= "<li class='page-item'><a class=\"page-link\" >...</a></li>";
            $pages .= "<li class='page-item'><a class=\"page-link\" href=\"{$url}&page={$last}\" >{$last}</a></li>";
        }
        return $pages;
    }

    private function export_csv($sql_sids){
        function outputCSV($list) {
            $output = fopen("php://output", "w");

            foreach ($list as $row) {
                fputcsv($output, $row, ';');
            }
            fclose($output);
        }
        $data_header[] = array('Наименование товарa','Цена в прайсе','Минимальная цена','Максимальная цена','Всего предложений','Категория');
        $data = \Yii::$app->db->createCommand("select ip.product_id, ip.basic_name, ROUND(ps.cost_by/10000,2) as seller_cost_by
									, ROUND(min(min_cost_by)/10000,2) as min_cost, ROUND(max(max_cost_by)/10000,2) as max_cost
									, sum(idx.cnt) as cnt_cost, ip.catalog_name, vcs.catalog_id
									from index_product_cost_data as idx, product_seller as ps, index_product as ip, v_catalog_sections as vcs
									where ps.seller_id = {$this->seller_id}
									and idx.product_id = ps.product_id
									and ip.product_id = ps.product_id and vcs.section_id = ip.index_section_id and idx.flag = 0 {$sql_sids}
									GROUP BY idx.product_id")->queryAll();
        foreach((array)$data as $r)
        {
            $rd['basic_name']=$r['basic_name'];
            $rd['seller_cost_by']=$r['seller_cost_by'];
            $rd['min_cost']=$r['min_cost'];
            $rd['max_cost']=$r['max_cost'];
            $rd['cnt_cost']=$r['cnt_cost'];
            $rd['catalog_name']=$r['catalog_name'];
            $data2[] = $rd;
        }

        $filename = "cost_analysis_{$this->seller_id}";

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename={$filename}.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        echo "\xEF\xBB\xBF"; // UTF-8 BOM


        $data_output = array_merge($data_header,$data2);

        outputCSV($data_output);
        exit;
    }


}
