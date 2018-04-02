<?php

namespace app\controllers;

use app\models\BillCatalog;
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
        }
    }

    public function actionIndex()
    {
        $vars = [];
        $vars['pack_items'] = $this->get_data_bill_catalog_new_tarif();
        $vars['pack_lines'] = $this->active_pack;
        $vars['pack_sum'] = $this->active_pack_sum;

        $vars['section_lines'] = $this->active_sections;
        $vars['section_sum'] = $this->active_sections_sum;

        $vars['all_sum'] = $this->active_pack_sum + $this->active_sections_sum;

        $vars['section_items'] = $this->get_data_bill_catalog_new_sections();
        return $this->render('index', $vars);
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
            where c.f_tarif=1 and c.hidden=0
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
