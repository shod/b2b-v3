<?php

namespace app\models_ex;

class BillAccount extends  \app\models\BillAccount {

    public function getDayDown($action = 0) {

        if (empty($day_down))
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

            $down_catalog = $res[0]["value"] * (100 - $this->skidka()) * 0.01;
            if (count($res)>1)
            {
                $down_catalog += $res[1]["value"];
            }


            $down_other = $res[0]['value'];

            //total + $down_other
            $day_down = $down_catalog ;
        }
        return $day_down;
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

}