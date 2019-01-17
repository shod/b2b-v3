<?php

namespace app\models_ex;


class BillAccount extends  \app\models\BillAccount {

    const MIN_BALANCE = 10;
    
    private $day_down;
    private $days_left;

    public function getDayDown($action = 0) {

        if (empty($this->day_down))
        {
            if ($action == 1){
                $sql_act = "";
            } else {
                $sql_act = " and ifnull(bbd.date_expired,DATE_ADD(now(),INTERVAL -1 DAY)) <= DATE_ADD(now(),INTERVAL -1 DAY) ";
            }

            // down_catalog
            $res = \Yii::$app->db->createCommand("
				select sum(bc.cost)/30 as value
				from bill_catalog_seller bcs
				inner join bill_catalog bc on (bc.id=bcs.catalog_id)
				left join bill_cat_sel_discount as bbd on (bbd.seller_id = bcs.seller_id and bbd.catalog_id = bcs.catalog_id)
				where bcs.seller_id in (select id from seller where bill_account_id={$this->id})
				{$sql_act}
				group by bc.f_tarif
				order by bc.f_tarif desc
			")->queryAll();

            if(count($res) > 0) {
                $down_catalog = $res[0]["value"] * (100 - $this->skidka()) * 0.01;
                if (count($res) > 1) {
                    $down_catalog += $res[1]["value"];
                }
                $this->day_down = $down_catalog;
            } else {
                $this->day_down = 0;
            }
        }
        return $this->day_down;
    }

    public function getDayDownCatalog($action = 0){
        $id = $this->id;
        if ($action == 1){
            $sql_act = "";
        } else {
            $sql_act = " and ifnull(bbd.date_expired,DATE_ADD(now(),INTERVAL -1 DAY)) <= DATE_ADD(now(),INTERVAL -1 DAY) ";
        }
        // down_catalog
        $res = \Yii::$app->db->createCommand("
				select sum(bc.cost)/30 as value
				from bill_catalog_seller bcs
				inner join bill_catalog bc on (bc.id=bcs.catalog_id)
				left join bill_cat_sel_discount as bbd on (bbd.seller_id = bcs.seller_id and bbd.catalog_id = bcs.catalog_id)
				where bcs.seller_id in (select id from seller where bill_account_id={$id})
				{$sql_act}
				group by bc.f_tarif
				order by bc.f_tarif desc
			")->queryAll();

        if(count($res) > 0){
            $down_catalog = $res[0]["value"] * (100 - $this->skidka()) * 0.01;

            if (count($res)>1)
            {
                $down_catalog += $res[1]["value"];
            }

            //total
            $day_down_catalog = $down_catalog;
        } else {
            $day_down_catalog = 0;
        }

        return $day_down_catalog;
    }

    public function skidka()
    {
        /**/
        return 0;

        if (empty($skidka))
        {
            $id = $this->get("id");
            $res = \Yii::$app->db->createCommand("
				select count(1) as cnt
                    from bill_catalog_seller bcs, bill_catalog as bc
                    where bcs.seller_id in (select id from seller where bill_account_id={$this->id}) and bcs.catalog_id in (select id from bill_catalog where f_tarif=1)
					and bc.id = bcs.catalog_id
					and bc.cost > 0
			")->queryAll();
            // skidki

            $cnt = min($res[0]["cnt"], 6);
            $this->skidka = $cnt ? ($cnt - 1) * 5 : 0;
        }
        return $skidka;
    }

    /*Get child account object*/
    public function getChildBillAccount(){
        $resdata = \Yii::$app->db->createCommand("select id from bill_account where owner_id = {$this->id}")->queryAll();
        if(count($resdata)){
            $child_id = $resdata[0]['id'];
            $baccount = BillAccount::find()->where(['id' => $child_id])->one();
        } else {
            $baccount = 0;
        }
        return $baccount;
    }

    
    function get_days_left()
    {

            $balance = $this->balance;
            $id = $this->id;
            $seller = Seller::find()->where(['bill_account_id' => $id])->one();

            if($seller->pay_type == 'clicks'){
                $days_left = $this->get_day_down_click();
            }else{
                $day_down = $this->get_day_down();

                if($day_down == 0){
                    $days_left = 30;
                }else{
                    $days_left = $day_down ? floor($balance / $day_down) : 0;
                }
            }
        return $days_left;
    }

    // суточный расход, если списания по кликам
    private function get_day_down_click()
    {
            $id = $this->id;
            
            /*По проценту*/
            if($seller->getFlag('type_order') != TRUE){
               $day_down = 10;
            }else{

                $res =\Yii::$app->db->createCommand("
                                    select ROUND(ba.balance_clicks / AVG(cnt_click+cnt_proxy)) as value
                                    from seller_clicks_stat as sc, seller as s
                                    , bill_account as ba
                                    where ba.id = {$id} and sc.seller_id = s.id
                                    and ba.id = s.bill_account_id
                                    order by sc.id desc
                                    limit 7;
                            ")->queryAll();
                $day_down = $res[0]["value"];
            }
        return $day_down;
    }

    // суточный расход
    private function get_day_down($action=0)
    {

            $id = $this->id;
            if ($action == 1){
                $sql_act = "";
            } else {
                $sql_act = " and ifnull(bbd.date_expired,DATE_ADD(now(),INTERVAL -1 DAY)) <= DATE_ADD(now(),INTERVAL -1 DAY) ";
            }
            // down_catalog
            $res = \Yii::$app->db->createCommand("
				select sum(bc.cost)/30 as value
				from bill_catalog_seller bcs
				inner join bill_catalog bc on (bc.id=bcs.catalog_id)
				left join bill_cat_sel_discount as bbd on (bbd.seller_id = bcs.seller_id and bbd.catalog_id = bcs.catalog_id)
				where bcs.seller_id in (select id from seller where bill_account_id={$id})
				{$sql_act}
				group by bc.f_tarif
				order by bc.f_tarif desc
			")->queryAll();

            if(count($res)){
                $down_catalog = $res[0]["value"] * (100 - $this->skidka()) * 0.01;
                if (count($res)>1)
                {
                    $down_catalog += $res[1]["value"];
                }
            } else {
                $down_catalog = 30;
            }


            //total + $down_other
            $day_down = $down_catalog ;

        return $day_down;
    }

}
