<?php

namespace app\controllers;

use app\helpers\ProductService;
use app\models\BillCatalog;
use app\models\Seller;
use app\models\SysStatus;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;


class TariffController extends Controller
{
    /**
     * @inheritdoc
     */
    public $seller_id;
    public $active_pack = "";
    public $active_pack_sum = 0;
    public $active_sections = "";
    public $active_sections_sum = 0;
    public function beforeAction($action) {
        if ((\Yii::$app->getUser()->isGuest)&&($action->id != 'login')&&($action->id != 'sign-up')) {
            $this->redirect('site/login');
        } else {
            return parent::beforeAction($action);
        }
    }

    public $rules =
        array(
            "products" => 183,
            "bill_catalog" => 182,
            "catalog" => 183,
            "import" => 184,
            "reviews" => 185,
            "billing" => 186,
            "order" => 187,
            "settings" => 188,
            "rules_placement" => 211
        );

    public function behaviors()
    {
        $this->seller_id = Yii::$app->user->identity->getId();
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

    public function actionProcess(){
        $action = Yii::$app->request->post("action");
        $action = isset($action) ? $action : Yii::$app->request->get("action");


        switch ($action) {
            case "save":
                $active_packs = json_decode(Yii::$app->request->get("pack"));
                $active_sections = json_decode(Yii::$app->request->get("section"));


                break;
            case "save_catalogs":
                $catalogs = Yii::$app->request->post("catalog_check");
                $good_ids = array();
                $good_ids[] = 0;
                foreach ((array)$catalogs as $id => $r)
                {
                    $good_ids[] = $id;
                }
                $str_good_ids = implode(",", $good_ids);
                $sql = "SELECT DISTINCT
                        vb.catalog_id
                    FROM
                        v_catalog_sections AS vb
                    WHERE
                        vb.hidden = 0
                    AND vb.section_id != 1
                    AND NOT EXISTS (
                        SELECT
                            1
                        FROM
                            bill_click_catalog_blacklist AS bcl
                        WHERE
                            seller_id = 0
                        AND vb.catalog_id = bcl.catalog_id
                    )
                    AND vb.catalog_id NOT IN ({$str_good_ids});";
                $res = \Yii::$app->db->createCommand($sql)->queryAll();
                \Yii::$app->db->createCommand("delete from bill_click_catalog_blacklist where seller_id = {$this->seller_id}")->execute();
                foreach ((array)$res as $r)
                {
                    \Yii::$app->db->createCommand("insert into bill_click_catalog_blacklist (catalog_id, seller_id) values ({$r['catalog_id']}, {$this->seller_id})")->execute();
                }
                \Yii::$app->db->createCommand("CALL pc_product_seller_actual({$this->seller_id});")->execute();
                return $this->redirect(['tariff/click']);
                break;
            case "refresh":
                \Yii::$app->db->createCommand("call pc_product_seller_actual({$this->seller_id});")->execute();
                return $this->redirect(['tariff/click']);
                break;
        }
    }

    public function actionIndex()
    {
        $seller = Seller::find()->where(['id' => $this->seller_id])->one();

        $curs = SysStatus::find()->where(['name' => 'curs_te'])->one()->value;



        $vars = [];
        $vars['curs'] = $curs;
        $vars['pack_items'] = $this->get_data_bill_catalog_new_tarif();
        $vars['pack_lines'] = $this->active_pack;
        $vars['pack_sum'] = $this->active_pack_sum;

        $vars['section_lines'] = $this->active_sections;
        $vars['section_sum'] = $this->active_sections_sum;

        $vars['all_sum'] = $this->active_pack_sum + $this->active_sections_sum;

        $vars['section_items'] = $this->get_data_bill_catalog_new_sections();
        return $this->render('index', $vars);
    }

    public function actionClick(){
        $vars = [];
        $sql = "SELECT DISTINCT
                            vb.catalog_id,
                            vb.`name`,
                            IF(qoff.catalog_id,1,0) AS cat_off
                        FROM
                            v_catalog_sections AS vb
                        LEFT JOIN (
                            SELECT
                                catalog_id
                            FROM
                                bill_click_catalog_blacklist AS bcl
                            WHERE
                                seller_id = {$this->seller_id}
                        ) AS qoff ON (
                            qoff.catalog_id = vb.catalog_id
                        )
                        join sections as s on (s.id = vb.section_id)
                        WHERE
                            vb.hidden = 0 and vb.section_id != 1
                        AND NOT EXISTS (
                            SELECT
                                1
                            FROM
                                bill_click_catalog_blacklist AS bcl
                            WHERE
                                seller_id = 0
                            AND vb.catalog_id = bcl.catalog_id
                        ) order by cat_off,  vb.name";
        $res = \Yii::$app->db->createCommand($sql)->queryAll();

        $sql = "select cs.catalog_id as id, count(ps.id) as cnt
                        from product_seller ps
                        inner join products p on (p.id=ps.product_id)
                        inner join catalog_subject cs on (cs.subject_id=p.section_id)
                        where ps.seller_id={$this->seller_id}
                        group by cs.catalog_id";
        $res_count = \Yii::$app->db->createCommand($sql)->queryAll();

        $sql = "SELECT
                            cs.catalog_id AS id,
                            count(ps.id) AS cnt
                        FROM
                            product_seller ps
                        INNER JOIN products p ON (p.id = ps.product_id)
                        INNER JOIN catalog_subject cs ON (
                            cs.subject_id = p.section_link_id
                        )
                        WHERE
                            ps.seller_id = {$this->seller_id}
                        AND ps.active = 1
                        AND p.section_id != p.section_link_id
                        AND NOT EXISTS (
                            SELECT
                                1
                            FROM
                                product_double_link
                            WHERE
                                product_id = ps.product_id
                        )
                        GROUP BY
                            cs.catalog_id";
        $res_goods = \Yii::$app->db->createCommand($sql)->queryAll();

        $data = array();
        foreach ((array)$res_count as $r)
        {
            $data[$r["id"]] = $r["cnt"];
        }

        $data_goods = array();
        foreach ((array)$res_goods as $r)
        {
            $data_goods[$r["id"]] = $r["cnt"];
        }
        $vars['data'] = "";
        foreach((array)$res as $r)
        {
            $r['count_products'] = isset($data[$r['catalog_id']]) && $data[$r['catalog_id']] > 0 ? $data[$r['catalog_id']] : "-";
            $r['count_goods'] = isset($data_goods[$r['catalog_id']]) && $data_goods[$r['catalog_id']] > 0 ? "<a href='/?admin=products&&catalog_id={$r['catalog_id']}&goods=1' >(+{$data_goods[$r['catalog_id']]} в товарах без описания)</a>" : "";
            $r['checked'] = $r['cat_off'] > 0 ? "" : "checked";
            $vars['data'] .= $this->renderPartial('tmpl/click_item', $r);

        }
        $vars["status"] = ProductService::getDateUpdate($this->seller_id);
        return $this->render('click', $vars);
    }

    private function get_data_bill_catalog_new_sections()
    {

        $html = '';

            $res1 = \Yii::$app->db->createCommand("
                select c.id, c.name, c.owner_id, c.hidden, c.position, c.f_tarif, c.is_old, c.f_new, c.pay_type, IFNULL(s.f_tarif,1) as f_mode_tarif, IFNULL(s.seller_id,0) as active, 
                if(ifnull(bbd.date_expired,DATE_ADD(now(),INTERVAL -1 DAY)) <= DATE_ADD(now(),INTERVAL -1 DAY) > 0, c.cost, 0) as cost
                from bill_catalog c
                left join bill_catalog_seller s on (s.seller_id={$this->seller_id} and s.catalog_id=c.id)
                left join bill_cat_sel_discount as bbd on (bbd.seller_id = s.seller_id and bbd.catalog_id = s.catalog_id)
                where c.hidden=0 and c.f_tarif = 0 and c.owner_id != 0
                order by active desc, c.name
            ")->queryAll();
            foreach ((array)$res1 as $i => $r1)
            {
                $ID1 = $r1['id'];
                $obj1 = new billPosition($ID1, $this->seller_id);

                $html .= $this->renderPartial('tmpl/item_section', array(
                    'id' => $ID1,
                    'f_tarif' => $r1['f_mode_tarif'] ? 1 : 0,
                    'f_tarif_class' => $r1['f_mode_tarif'] ? 'mode_tarif' : '',
                    'name' => $obj1->name,
                    'cost' => $obj1->get_cost_str(),
                    "act" => $obj1->get_act_str(),
                    "checked" => $obj1->is_active() ? "checked" : "",
                    'class_last' => ($i + 1 == count($res1)) ? 'class="last"' : ''
                ));
            }

        return $html;
    }

    private function get_data_bill_catalog_new_tarif()
    {

        $res = \Yii::$app->db->createCommand("
            select c.id, c.name, c.owner_id, c.hidden, c.position, c.f_tarif, c.is_old, c.f_new, c.pay_type, IFNULL(s.f_tarif,1) as f_mode_tarif, IFNULL(s.seller_id,0) as active, 
            if(ifnull(bbd.date_expired,DATE_ADD(now(),INTERVAL -1 DAY)) <= DATE_ADD(now(),INTERVAL -1 DAY) > 0, c.cost, 0) as cost
            from bill_catalog c
            left join bill_catalog_seller s on (s.seller_id={$this->seller_id} and s.catalog_id=c.id)
            left join bill_cat_sel_discount as bbd on (bbd.seller_id = s.seller_id and bbd.catalog_id = s.catalog_id)
            where c.f_tarif=1 and c.hidden=0  and (c.id >= 588 or c.id in (223,261))
			  and (c.is_old = 0 OR (s.f_tarif =1 and c.is_old=1))
			  order by active desc, c.name
        ")->queryAll();
        $html = '';
        foreach ((array)$res as $r)
        {
            $ID = $r['id'];
            $obj = new billPosition($ID, $this->seller_id);

            $cost = $obj->get_cost_str();
            $html .= $this->renderPartial("tmpl/pack_item", array(
                'id' => $ID,
                'f_tarif' => $r['f_mode_tarif'] ? 1 : 0,
                'f_tarif_class' => $r['f_mode_tarif'] ? 'mode_tarif' : '',
                "name" => $obj->name,
                "cost" => $cost,
                "checked" => $obj->is_active() ? "checked" : "",
                "active_style" => $obj->is_active() ? "background-color: rgba(0,0,0,.05)" : "",
                "act" => $obj->get_act_str(),
                "sections" => $obj->get_tarif_sections_html(),
                'evalue' => max($obj->get_economy(), 0)
            ));
            if($obj->is_active()){
                $this->active_pack .= $this->renderPartial("tmpl/pack_line", ['cost' => $cost, 'name' => $obj->name, 'id' => $obj->id]);
                $this->active_pack_sum += $cost['cost'];
            }

        }

        return $html;
    }

}
