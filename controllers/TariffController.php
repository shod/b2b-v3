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
    public $min_balance = -10;
	public $tariff_version = 0;
	
    public function beforeAction($action) {
        if ((\Yii::$app->getUser()->isGuest)&&($action->id != 'login')&&($action->id != 'sign-up')) {
            $this->redirect('/site/login');
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
		$this->tariff_version = Yii::$app->params['tariff_version'];
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
                $ids = array_merge($active_sections,$active_packs);

                $seller = \app\models\Seller::find()->where(['id' => $this->seller_id])->one();
                $bill_account = \app\models_ex\BillAccount::find()->where(['id' => $seller->bill_account_id])->one();
                if(($bill_account->balance <= $this->min_balance) && ($this->seller_id != 4129)){
                    echo "Для смены тарифа ваш баланс должен быть не менее " .  $this->min_balance ."!";
                    exit;
                }

                $DATA = array();
                $ids_done = array();
                $res = \Yii::$app->db->createCommand("select * from bill_catalog_seller where seller_id={$this->seller_id}")->queryAll();

                foreach((array)$res as $r)
                {
                    $id = $r['catalog_id'];
                    if (in_array($id, $ids))
                    {
                        $ids_done[] = $id;
                    }
                    else
                    {
                        $DATA[] = array('section_deactivate', $id);
                    }
                }

                foreach((array)$ids as $id)
                {
                    if (!in_array($id, $ids_done))
                    {
                        $DATA[] = array('section_activate', $id);
                    }
                }
                \Yii::$app->billing->transaction($this->seller_id, 'section_group', $DATA);

                /* Обновление активности товаров в зависимости от подключенных разделов*/
                $sql = "update product_seller as ps
                set active=0
                where seller_id = {$this->seller_id}";
                \Yii::$app->db->createCommand($sql)->execute();

                \Yii::$app->db->createCommand("call pc_cost_round({$this->seller_id});")->execute();
                \Yii::$app->db->createCommand("call pc_product_seller_actual({$this->seller_id});")->execute();
                echo "Тариф успешно сохранен!";
                exit();
                break;
            case "save_catalogs":
                $page_back = Yii::$app->request->post("page");
                $page_back = isset($page_back) ? $page_back : 'tariff/click';
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

                return $this->redirect([$page_back]);
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

        $regime_tarif = $this->tariff_version.'';

        
        if(!in_array($this->seller_id,\Yii::$app->params['seller_old_tariff'])){
			$procedure = 'get_data_bill_catalog_tarif_'.$regime_tarif;
            $vars['pack_items'] = $this->$procedure();        
        }else{
            $vars['pack_items'] = $this->get_data_bill_catalog_new_tarif();
            $vars['section_items'] = $this->get_data_bill_catalog_new_sections();
        }

		$vars['pack_lines'] = $this->active_pack;
        $vars['pack_sum'] = $this->active_pack_sum;

        

        /*if($this->seller_id == 1500){
            $vars['pack_items'] = $this->get_data_bill_catalog_tarif_2();
        }else{
            $vars['pack_items'] = $this->get_data_bill_catalog_new_tarif();
        }*/
        
        $vars['section_lines'] = $this->active_sections;
        $vars['section_sum'] = $this->active_sections_sum;

        $vars['all_sum'] = $this->active_pack_sum + $this->active_sections_sum;        

        $tmpl_name = 'index';
        if($regime_tarif > 0){
            $tmpl_name = 'index_'.$regime_tarif;
            $vars['data'] = $this->getCatalogBlackList($this->seller_id);
        }

        return $this->render($tmpl_name, $vars);
    }

    public function actionClick(){       
        $seller = Seller::find()->where(['id' => $this->seller_id])->one();        
        
        $vars = [];
        

        

        $vars['data'] = $this->getCatalogBlackList($this->seller_id);

        /*Настройки контактов*/
        $vorder["is_order"] = ($seller->getFlag('is_order')) ? "checked" : "";        
        $vorder["is_phone"] = ($seller->getFlag('is_phone')) ? "checked" : "";        
        $vorder["proxysite"] = ($seller->getFlag('proxysite')) ? "checked" : ""; 
        $vorder["prc_order"] = $this->getPrcSetting($seller)*100;
        $vorder["prc_phone"] = ($vorder["prc_order"]/10);
        $vorder["prc_proxy"] = ($vorder["prc_order"]/10*2);
        
        $vars['order_setting'] = $this->renderPartial('tmpl/order_setting',$vorder);
        
        $vars["status"] = ProductService::getDateUpdate($this->seller_id);
                
        return $this->render('click', $vars);
    }


    private function getCatalogBlackList($seller_id){
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
                                seller_id = {$seller_id}
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
                            ps.seller_id = {$seller_id}
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
        $ResData = "";
        foreach((array)$res as $r)
        {
            $r['count_products'] = isset($data[$r['catalog_id']]) && $data[$r['catalog_id']] > 0 ? $data[$r['catalog_id']] : "-";
            $r['count_goods'] = isset($data_goods[$r['catalog_id']]) && $data_goods[$r['catalog_id']] > 0 ? "<a href='/product/catalog/?catalog_id={$r['catalog_id']}&goods=1' >(+{$data_goods[$r['catalog_id']]} в товарах без описания)</a>" : "";
            $r['checked'] = $r['cat_off'] > 0 ? "" : "checked";
            $ResData .= $this->renderPartial('tmpl/click_item', $r);

        }

        return  $ResData;
    }

    /*public function actionTarif2()
    {
        $seller = Seller::find()->where(['id' => $this->seller_id])->one();

        $curs = SysStatus::find()->where(['name' => 'curs_te'])->one()->value;



        $vars = [];
        $vars['curs'] = $curs;

        if($this->seller_id == 1500){
            $vars['pack_items'] = $this->get_data_bill_catalog_tarif_2();
        }else{
            $vars['pack_items'] = $this->get_data_bill_catalog_new_tarif();
        }

        $vars['pack_lines'] = $this->active_pack;
        $vars['pack_sum'] = $this->active_pack_sum;
        $vars['section_items'] = $this->get_data_bill_catalog_new_sections();
        $vars['section_lines'] = $this->active_sections;
        $vars['section_sum'] = $this->active_sections_sum;

        $vars['all_sum'] = $this->active_pack_sum + $this->active_sections_sum;        
               
        return $this->render('index_2', $vars);
    }*/

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
                $cost = $obj1->get_cost_str();
                $html .= $this->renderPartial('tmpl/item_section', array(
                    'id' => $ID1,
                    'f_tarif' => $r1['f_mode_tarif'] ? 1 : 0,
                    'f_tarif_class' => $r1['f_mode_tarif'] ? 'mode_tarif' : '',
                    'name' => $obj1->name,
                    'cost' => $cost,
                    "act" => $obj1->get_act_str(),
                    "checked" => $obj1->is_active() ? "checked" : "",
                    'class_last' => ($i + 1 == count($res1)) ? 'class="last"' : ''
                ));
                if($obj1->is_active()){
                    $this->active_sections .= $this->renderPartial("tmpl/section_line", ['cost' => $cost, 'name' => $obj1->name, 'id' => $obj1->id]);
                    $this->active_sections_sum += $cost['cost'];
                }
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

    private function get_data_bill_catalog_tarif_2()
    {
        $bonus_value[240] = ['bonus' => '0', 'text' => 'Размещайте все свои товары во всех разделах!', 'prod_count' => 'неограничено'];
        $bonus_value[120] = ['bonus' => '0', 'text' => 'Нет ограничений на разделы!', 'prod_count' => 'до 5000'];
		$bonus_value[60] = ['bonus' => '0', 'text' => 'Нет ограничений на разделы!', 'prod_count' => 'до 1000'];
        $bonus_value[20] = ['bonus' => '0', 'text' => 'Нет ограничений на разделы!', 'prod_count' => 'до 1000'];

        $res = \Yii::$app->db->createCommand("
        select c.id, c.name, c.owner_id, c.hidden, c.position, c.f_tarif, c.is_old, c.f_new, c.pay_type, IFNULL(s.f_tarif,1) as f_mode_tarif, IFNULL(s.seller_id,0) as active, 
                    if(ifnull(bbd.date_expired,DATE_ADD(now(),INTERVAL -1 DAY)) <= DATE_ADD(now(),INTERVAL -1 DAY) > 0, c.cost, 0) as cost
                    from bill_catalog c
                    left join bill_catalog_seller s on (s.seller_id={$this->seller_id} and s.catalog_id=c.id)
                    left join bill_cat_sel_discount as bbd on (bbd.seller_id = s.seller_id and bbd.catalog_id = s.catalog_id)
                    where c.f_tarif=2 and c.hidden=0  
                      order by c.name;
        ")->queryAll();
        $html = '';
        foreach ((array)$res as $r)
        {
            $ID = $r['id'];
            $obj = new billPosition($ID, $this->seller_id);

            $cost = $obj->get_cost_str();
            $html .= $this->renderPartial("tmpl/pack_item2", array(
                'id' => $ID,
                'f_tarif' => $r['f_mode_tarif'] ? 1 : 0,
                'f_tarif_class' => $r['f_mode_tarif'] ? 'mode_tarif' : '',
                "name" => $obj->name,
                "cost" => $cost,
                "checked" => $obj->is_active() ? "checked" : "",
                "active_style" => $obj->is_active() ? "background-color: rgba(0,0,0,.05)" : "",
                "act" => $obj->get_act_str(),
                "sections" => $obj->get_tarif_sections_html(),
                'evalue' => max($obj->get_economy(), 0),
                'bonus_value' => $bonus_value[$cost['cost']]
            ));
            if($obj->is_active()){
                $this->active_pack .= $this->renderPartial("tmpl/pack_line", ['cost' => $cost, 'name' => $obj->name, 'id' => $obj->id]);
                $this->active_pack_sum += $cost['cost'];
            }

        }

        return $html;
    }
	
	private function get_data_bill_catalog_tarif_3()
    {

        $bonus_value[20] = ['bonus' => '0', 'text' => 'Нет ограничений на разделы!', 'prod_count' => 'до 1000'];

        $res = \Yii::$app->db->createCommand("
        select c.id, c.name, c.owner_id, c.hidden, c.position, c.f_tarif, c.is_old, c.f_new, c.pay_type, IFNULL(s.f_tarif,1) as f_mode_tarif, IFNULL(s.seller_id,0) as active, 
                    if(ifnull(bbd.date_expired,DATE_ADD(now(),INTERVAL -1 DAY)) <= DATE_ADD(now(),INTERVAL -1 DAY) > 0, c.cost, 0) as cost
                    from bill_catalog c
                    left join bill_catalog_seller s on (s.seller_id={$this->seller_id} and s.catalog_id=c.id)
                    left join bill_cat_sel_discount as bbd on (bbd.seller_id = s.seller_id and bbd.catalog_id = s.catalog_id)
                    where c.f_tarif=3 and c.hidden=0  
                      order by c.name;
        ")->queryAll();
        $html = '';
        foreach ((array)$res as $r)
        {
            $ID = $r['id'];
            $obj = new billPosition($ID, $this->seller_id);

            $cost = $obj->get_cost_str();
            $html .= $this->renderPartial("tmpl/pack_item3", array(
                'id' => $ID,
                'f_tarif' => $r['f_mode_tarif'] ? 1 : 0,
                'f_tarif_class' => $r['f_mode_tarif'] ? 'mode_tarif' : '',
                "name" => $obj->name,
                "cost" => $cost,
                "checked" => $obj->is_active() ? "checked" : "",
                "active_style" => $obj->is_active() ? "background-color: rgba(0,0,0,.05)" : "",
                "act" => $obj->get_act_str(),
                "sections" => '',//$obj->get_tarif_sections_html(),
                'evalue' => max($obj->get_economy(), 0),
                'bonus_value' => $bonus_value[$cost['cost']]
            ));
			
            if($obj->is_active()){
                $this->active_pack .= $this->renderPartial("tmpl/pack_line", ['cost' => $cost, 'name' => $obj->name, 'id' => $obj->id]);
                $this->active_pack_sum += $cost['cost'];
            }

        }

        return $html;
    }


     public function actionSaveOrderSettings(){
        $ordertype = Yii::$app->request->get("ordertype");
        $action = Yii::$app->request->get("action");
        $seller = Seller::find()->where(['id' => $this->seller_id])->one();        

        switch ($action){
            case "active":                
                $seller->setFlag($ordertype, 'true');
                $seller->save();                
                echo 'Активация прошла успешно!';
                break;
            case "deactive":                
                $seller->setFlag($ordertype, 'false');
                $seller->save();                
                echo 'Деактивация прошла успешно!';
                break;
        }
    }
    
    /*Данные по базовой процентной ставке*/
    private function getPrcSetting($seller) {
        $seller_prc = $seller->sellerInfo->po_prc;
        if($seller_prc > 0){
            return $seller_prc;
        }else{
            return \app\helpers\SysService::get('seller_order_prc');
        }
    }
}
