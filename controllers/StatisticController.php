<?php

namespace app\controllers;

use app\helpers\SiteService;
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

    public function beforeAction($action) {
        if ((\Yii::$app->getUser()->isGuest)&&($action->id != 'login')&&($action->id != 'sign-up')) {
            $this->redirect('site/login');
        } else {
            return parent::beforeAction($action);
        }
    }
    
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

    public function actionGetAccess(){
        $json["header"] = 'Получить доступ к анализу цен';
        $json["body"] = $this->renderPartial('tmpl/analysis_access');
        echo Json::encode($json);
    }

    public function  actionGetChart(){
        $date = Yii::$app->request->get('date');
        $arr_date = explode("-", $date);
        $year = $arr_date[0];
        $month = $arr_date[1];

        $sql = "SELECT
                                *
                            FROM
                                seller_clicks_stat
                            WHERE
                                seller_id = {$this->seller_id}
                            AND YEAR(date_stat) = {$year} AND MONTH(date_stat) = {$month}
                            ORDER BY
                                date_stat DESC";
        $data = \Yii::$app->db->createCommand($sql)->queryAll();
        $array_data = array();
        foreach((array)$data as $r)
        {
            $a['date_view'] = str_replace("-", ", ",$r['date_stat']);
            $a['view'] = $r['cnt_click'];
            $a['view_proxy'] = $r['cnt_proxy'];
            $a['view_context'] = $r['cnt_click_context'];
            $array_data[]= $a;
        }
        $json = Json::encode($array_data);
        echo $json;
        exit;
    }

    public function  actionGetChartHours(){
        $date = Yii::$app->request->get('date');
        $sql = "SELECT
					DATE_FORMAT(from_unixtime(created_at),'%H') as hours, COUNT(1) as cnt
					FROM
						migombyha.stat_popup
					WHERE
						seller_id = {$this->seller_id}
					AND DATE_FORMAT(
						FROM_UNIXTIME(created_at),
						'%Y-%m-%d'
					) = '{$date}'
					AND f_uniq = 1
					GROUP BY DATE_FORMAT(from_unixtime(created_at),'%H')
					ORDER BY
						created_at ASC";
        $data = \Yii::$app->db->createCommand($sql)->queryAll();
        $array_data = array();
        foreach((array)$data as $r)
        {
            $a['date_view'] = $r['hours'];
            $a['view'] = $r['cnt'];
            $array_data[]= $a;
        }
        $json = json_encode($array_data);
        echo $json;
        exit;
    }

    public function actionCostAnalysisCsv(){
        $sids = Yii::$app->request->get('sids');
        $sql_sids = $sids ? " and idx.seller_id not in ({$sids})" : "";
        $this->export_csv($sql_sids);
        exit;
    }


    public function actionIndex()
    {
        $vars = [];
        $sql = "select po_active from seller_info where  seller_id = {$this->seller_id}";
        $res = \Yii::$app->db->createCommand($sql)->queryOne();
        if (count($res) == 1){
            if ($res['po_active'] == 0){
                $vars["po_active"]  = "<br><a href='/order/sms' style='color:red'>Услуга отключена</a>";
                $vars["po_sms_alert"]  = "<p>Чтобы не терять клиентов подключите услугу <a href='/order/sms' style='color:red'>SMS-заказы</a></p>";
            }
        }
        $stat_interval_month = 6;
        $sql = "SELECT
											date_format(
												from_unixtime(`q_show`.`date`),
												'%Y-%m'
											) AS `date`,
											`q_show`.`seller_id` AS `seller_id`,
											sproxy.cnt,
											sproxy.avg_day,
											IFNULL(sum(`q_show`.`view`),0) AS `view_stat`,
											round(
												(sum(`q_show`.`view`) / 30),
												0
											) AS `view_stat_day`,
											IFNULL(s_order.view_order,0) as view_order,
											IFNULL(stat_contact.cnt,0) AS count_contact,
											IFNULL(stat_go.cnt,0) AS count_sms,
                                            IFNULL(context, 0) as context
										FROM
											migombyha.`product_seller_stat` AS `q_show`
										LEFT JOIN migombyha.v_stats_seller_proxy AS sproxy ON (
											sproxy.seller_id = `q_show`.seller_id AND sproxy.date = date_format(
											from_unixtime(`q_show`.`date`),
											'%Y-%m'
										)
										)
										LEFT JOIN (
											SELECT
												seller_id,
												DATE_FORMAT(
													FROM_UNIXTIME(date),
													'%Y-%m'
												) AS mdat,
												count(*) AS view_order
											FROM
												stat_order_buffer
											WHERE
												date > UNIX_TIMESTAMP('2015-11-01') and 
												date > UNIX_TIMESTAMP(
													(
														DATE_ADD(now(), INTERVAL - {$stat_interval_month} MONTH)
													)
												)
											GROUP BY
												seller_id,
												DATE_FORMAT(
													FROM_UNIXTIME(date),
													'%Y-%m'
												)
										) AS s_order ON (
											s_order.seller_id = `q_show`.seller_id
											AND s_order.mdat = date_format(
												from_unixtime(`q_show`.`date`),
												'%Y-%m'
											)
										)

										LEFT JOIN (SELECT
											seller_id,
											FROM_UNIXTIME(created_at, '%Y-%m') AS cdate,
											count(*) AS cnt,
                                            SUM(context) as context
										FROM
											migombyha.stat_popup
										WHERE
											seller_id = {$this->seller_id}
											AND f_uniq = 1
											AND created_at > UNIX_TIMESTAMP('2015-11-01') and created_at > UNIX_TIMESTAMP(
											(
												DATE_ADD(now(), INTERVAL - {$stat_interval_month} MONTH)
											)
										)
										GROUP BY
											seller_id,
											FROM_UNIXTIME(created_at, '%Y-%m')) as stat_contact on (stat_contact.cdate = date_format(
												from_unixtime(`q_show`.`date`),
												'%Y-%m'
											))

										LEFT JOIN (select DATE_FORMAT(created_at,'%Y-%m') as dat, count(1) as cnt
															from po_order as po
															where seller_id = {$this->seller_id} and 
															po.created_at > '2015-11-01'
															group by dat) as stat_go on (stat_go.dat = date_format(
												from_unixtime(`q_show`.`date`),
												'%Y-%m'
											))

										WHERE
											`q_show`.seller_id = {$this->seller_id}
										AND `q_show`.`date` > UNIX_TIMESTAMP('2015-11-01') and `q_show`.`date` > UNIX_TIMESTAMP(
											(
												DATE_ADD(now(), INTERVAL - {$stat_interval_month} MONTH)
											)
										)
										
										GROUP BY
											`q_show`.`seller_id`,
											date_format(
												from_unixtime(`q_show`.`date`),
												'%Y-%m'
											)";
        $res = \Yii::$app->db->createCommand($sql)->queryAll();
        $vars['data'] = '';
        foreach((array)$res as $r)
        {
            $vars['data'] .= $this->renderPartial('tmpl/all-month-item', $r);
        }

        $sql = "SELECT
								f.id,
								f.seller_id,
								f.phone,
								FROM_UNIXTIME(f.created_at) AS date,
								f.product_id,
								f.status,
								si.po_balance,
								si.po_phone,
								si.po_email,
								s.work_time,

							IF (
								po_active = 1
								AND po_balance > 0
								AND (
									(po_phone IS NOT NULL)
									OR (po_email IS NOT NULL)
								),
								1,
								0
							) AS po_active,
							 s.name
							FROM
								migombyha.stat_seller_phone_fail f
							JOIN migomby.seller_info si ON (f.seller_id = si.seller_id)
							JOIN migomby.seller AS s ON (s.id = si.seller_id)
							WHERE
								f. STATUS = 0
                            AND FROM_UNIXTIME(f.created_at) > (DATE_SUB(NOW(), INTERVAL 1 MONTH))
							AND f.seller_id = {$this->seller_id} order by f.created_at desc";
        $res = \Yii::$app->db->createCommand($sql)->queryAll();

        $vars['data_complaint'] = '';
        foreach((array)$res as $r)
        {
            $vars['data_complaint'] .= $this->renderPartial('tmpl/complaint-item', $r);
        }

        return $this->render('index', $vars);
    }

    public function actionMonth()
    {
        $vars = [];
        $date = Yii::$app->request->get("date");
        $vars['date'] = $date;
        $sql = "CALL pc_seller_click_stat({$this->seller_id})";
        $res = \Yii::$app->db->createCommand($sql)->execute();

        if ($date){
            $arr_date = explode("-", $date);
            $year = $arr_date[0];
            $month = $arr_date[1];

            $sql = "SELECT
                                *
                            FROM
                                seller_clicks_stat
                            WHERE
                                seller_id = {$this->seller_id}
                            AND YEAR(date_stat) = {$year} AND MONTH(date_stat) = {$month}
                            ORDER BY
                                date_stat DESC";
            $sql2 = "SELECT 
							IF(vcat.`name` is not null,vcat.`name`,'Другие клики') as name,
							 COUNT(*) as cnt
							FROM
								migombyha.stat_popup as sp
								LEFT JOIN products as p on (p.id = sp.product_id)
								LEFT JOIN v_catalog_sections as vcat on (vcat.section_id = p.section_id)
							WHERE
							 seller_id = {$this->seller_id}
							AND DATE_FORMAT(
							 FROM_UNIXTIME(created_at),
							 '%Y-%m'
							) = '{$date}'
							AND f_uniq = 1
							GROUP BY vcat.catalog_id ORDER BY cnt desc";
        } else {
            $sql = "select * from seller_clicks_stat where seller_id = {$this->seller_id} and date_stat > DATE_FORMAT(DATE_SUB(NOW(),INTERVAL 2 MONTH),'%Y-%m-01')  ORDER BY date_stat desc;";
            $sql2 = "SELECT 
							 vcat.`name`,
							 COUNT(*) as cnt
							FROM
							 migombyha.stat_popup as sp, products as p, v_catalog_sections as vcat
							WHERE
							 seller_id = {$this->seller_id}
							and created_at > DATE_FORMAT(DATE_SUB(NOW(),INTERVAL 2 MONTH),'%Y-%m-01')
							AND f_uniq = 1
							and p.id = sp.product_id and vcat.section_id = p.section_id
							GROUP BY vcat.catalog_id";
        }
        $res = \Yii::$app->db->createCommand($sql)->queryAll();
        $res2 = \Yii::$app->db->createCommand($sql2)->queryAll();
        $vars['data'] = "";
        $vars['data_sections'] = "";
        foreach((array)$res as $r)
        {
            $vars['data'] .= $this->renderPartial('tmpl/month-data-item', $r);
        }

        foreach((array)$res2 as $r)
        {
            $vars['data_sections'] .= $this->renderPartial('tmpl/month-data-section-item', $r);
        }
        return $this->render('month', $vars);
    }

    public function actionGetChartCtr(){
        $date = Yii::$app->request->get("date");
        $arr_date = explode("-", $date);
        $year = $arr_date[0];
        $month = $arr_date[1];


        $sql = "SELECT ROUND(AVG(ss.ctr_popup)*100,2) as ctr, ss.date, ROUND(AVG(scs.ctr_popup)*100,2) as ctr_all FROM migombyha.`seller_ctr_stat` as ss
					left join migombyha.seller_ctr_stat as scs on (scs.date = ss.date)
					where ss.seller_id = {$this->seller_id} and
					DATE_FORMAT(
						ss.date,
						'%Y-%m'
					) = '{$year}-{$month}'  and scs.ctr_popup < 1 and  ss.ctr_popup < 1
					 GROUP BY ss.date order by ss.date desc";
        $data = \Yii::$app->db->createCommand($sql)->queryAll();
        $array_data = array();
        foreach((array)$data as $r)
        {
            $a['date_view'] = str_replace("-", ", ",$r['date']);
            $a['view'] = $r['ctr'];
            $a['view_all'] = $r['ctr_all'];
            $array_data[]= $a;
        }
        $json = json_encode($array_data);
        echo $json;
        exit;
    }

    public function actionGetDayStat(){
        $date = Yii::$app->request->get("date");
        $sql = "SELECT *, DATE_FORMAT(FROM_UNIXTIME(created_at), '%H:%i') as datetime from migombyha.stat_popup WHERE seller_id = {$this->seller_id} and DATE_FORMAT(FROM_UNIXTIME(created_at),'%Y-%m-%d') = '{$date}' and f_uniq = 1 ORDER BY created_at asc";
        $res = \Yii::$app->db->createCommand($sql)->queryAll();
        $data = "<h4>Дата: {$date}</h4><table class='table table-bordered table-condensed table-striped' style='word-wrap: break-word; table-layout:fixed;'>";
        $data .= "<tr><th colspan=4>Статистика кликов</th></tr>";
        foreach((array)$res as $r)
        {
            $r['context'] = isset($r['context']) ? "Контекст" : "";
            if($r['product_id'] != 0){
                $sql = "SELECT basic_name from index_product WHERE product_id = {$r['product_id']}";
                $name_product = \Yii::$app->db->createCommand($sql)->queryAll();
                if (count($name_product) > 0){
                    $name = $name_product[0]['basic_name'];
                } else {
                    $name = $r['product_id'];
                }
                $r['ref_url'] = "Товар: <a href='http://www.migom.by/{$r['product_id']}/' target='_blank'>{$name}</a>";
            }
            $data .= $this->renderPartial('tmpl/month-data-item-report', $r);
        }
        $data .= "<tr><th colspan=4>Статистика переходов</th></tr>";
        $sql = "SELECT *, DATE_FORMAT(FROM_UNIXTIME(created_at), '%H:%i') as datetime from migombyha.stat_proxy WHERE seller_id = {$this->seller_id} and DATE_FORMAT(FROM_UNIXTIME(created_at),'%Y-%m-%d') = '{$date}' ORDER BY created_at asc";
        $res = \Yii::$app->db->createCommand($sql)->queryAll();
        foreach((array)$res as $r)
        {
            $r['context'] = isset($r['context']) ? "Контекст" : "";
            $data .= $this->renderPartial('tmpl/month-data-item-report', $r);
        }
        $data .= "</table>";
        echo $data;
        exit;
    }

    public function actionGetStatGroup(){
        $date = Yii::$app->request->get("date");
        $sql = "SELECT 
					 vcat.`name`,
					 COUNT(*) as cnt
					FROM
					 migombyha.stat_popup as sp, products as p, v_catalog_sections as vcat
					WHERE
					 seller_id = {$this->seller_id}
					AND DATE_FORMAT(
					 FROM_UNIXTIME(created_at),
					 '%Y-%m-%d'
					) = '{$date}'
					AND f_uniq = 1
					and p.id = sp.product_id and vcat.section_id = p.section_id
					GROUP BY vcat.catalog_id  ORDER BY cnt desc";
        $res = \Yii::$app->db->createCommand($sql)->queryAll();
        $data = "<h4>Дата: {$date}</h4><table class='table table-bordered table-condensed table-striped' style='word-wrap: break-word; table-layout:fixed;'>";
        $data .= "<tr><th colspan=4>Статистика кликов</th></tr>";
        foreach((array)$res as $r)
        {
            $data .= $this->renderPartial('tmpl/month-data-item-group', $r);
        }
        $data .= "</table>";
        echo $data;
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
        $url = "/statistic/cost-analysis/?";

        $pages = SiteService::get_pages($page, $first, $last, $url);
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
