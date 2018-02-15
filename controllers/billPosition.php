<?php

/**
 * Created by PhpStorm.
 * User: Миг-к101
 * Date: 14.02.2018
 * Time: 14:18
 */
namespace app\controllers;
class billPosition
{
    var $id, $name, $_is_active;

    public function __construct($id, $seller_id, $r=false)
    {
        $this->id = $id;
        $this->seller_id = $seller_id;

        if (!$r){
            //list($this->name, $this->hidden, $this->cost, $this->f_tarif) = $whirl->dbd->tables['bill_catalog']->get_properties($this->id, array('name', 'hidden', 'cost', 'f_tarif'));
            $bill_catalog = \app\models\BillCatalog::find()->where(['id' => $this->id])->one();
            $this->name = $bill_catalog->name;
            $this->hidden = $bill_catalog->hidden;
            $this->cost = $bill_catalog->cost;
            $this->f_tarif = $bill_catalog->f_tarif;
        } else {
            $this->name = $r['name'];
            $this->hidden = $r['hidden'];
            $this->cost = $r['cost'];
            $this->f_tarif = $r['f_tarif'];
            $this->_is_active = ($r['active'] > 0);
        }
    }

    public function get_cost_str()
    {
        $cost = $this->cost;
        $c1 = $cost ? round($cost / 30, 2) : 0;
        return ["cost" => $cost, 'c1' => $c1];
    }

    public function get_tarif_sections_html()
    {
        $res = \Yii::$app->db->createCommand("
            select s.name
            from bill_catalog_section bcs
            inner join sections s on (s.id=bcs.section_id)
            where bcs.catalog_id={$this->id}
            order by s.name
        ")->queryAll();

        $sections = array();
        foreach ((array)$res as $r)
            $sections[] = $r['name'];

        $html = join(', ', $sections);
        return $html;
    }

    public function is_active()
    {
        if (is_null($this->_is_active)) {
            $res = \Yii::$app->db->createCommand("select catalog_id from bill_catalog_seller where seller_id={$this->seller_id} and catalog_id={$this->id}")->queryAll();
            $this->_is_active = (count($res) > 0);
        }

        return $this->_is_active;
    }

    public function get_cnt_ps()
    {
        if (is_null($this->_cnt_ps)) {
            global $whirl;
            $res = $whirl->dbd->query("
                    select count(1)
                    from bill_catalog_section bcs
                    inner join products p on (p.section_id=bcs.section_id)
                    inner join product_seller ps on (ps.product_id=p.id)
                    where bcs.catalog_id={$this->id} and ps.seller_id={$this->seller_id}
            ");
            $this->_cnt_ps = $res[0][0];
        }

        return $this->_cnt_ps;
    }

    public function get_cnt_ps_sec()
    {
        if (is_null($this->_cnt_ps_sec)) {
            global $whirl;
            $res = $whirl->dbd->query("
                    SELECT
                        count(1)
                    FROM
                        bill_catalog_section bcs
                    INNER JOIN bill_catalog AS bc ON (bc.id = bcs.catalog_id)
                    INNER JOIN products p ON (
                        p.section_id = bcs.section_id
                    )
                    INNER JOIN product_seller ps ON (ps.product_id = p.id)
                    WHERE
                        bc.owner_id = {$this->id}
                    AND ps.seller_id = {$this->seller_id}
            ");
            $this->_cnt_ps_sec = $res[0][0];
        }

        return $this->_cnt_ps_sec;
    }

    public function get_act_str_sec()
    {
        $cnt = $this->get_cnt_ps_sec();
        if ($this->is_active()) {
            $act_str = "<span class=\"act\"> активный ($cnt)</span>";
        }
        else
        {
            $act_str = $cnt ? "<span class=\"red\">доступно ({$cnt})</span>" : "";
        }
        return $act_str;
    }

    public function get_act_str()
    {
        $cnt = $this->get_cnt_ps();
        if ($this->is_active()) {
            $act_str = "<span class=\"act\"> активный ($cnt)</span>";
        }
        else
        {
            $act_str = $cnt ? "<span class=\"red\">доступно ({$cnt})</span>" : "";
        }
        return $act_str;
    }

    public function get_economy()
    {
        $res = \Yii::$app->db->createCommand("
            select sum(cost) as cost from (
            select min(cost) as cost
            from bill_catalog_section as bcs
            inner join bill_catalog_section as bcs1 on (bcs1.section_id = bcs.section_id and bcs1.catalog_id<>bcs.catalog_id)
            inner join bill_catalog as bc on (bc.id = bcs1.catalog_id)
            where bcs.catalog_id = {$this->id} and bc.owner_id >0
            group by bcs1.section_id
            ) as query
        ")->queryAll();
        return $res[0]['cost'] - $this->cost;
    }

}