<?php

namespace app\models_ex;

class BillTransactionType extends  \app\models\BillTransactionType {

    public function getDescription($object_id){
        $id = $this->id;
        $name = $this->name;
        switch ($this->code)
        {
            case "up_promice_pay":
            case "up_card":
                $res = \Yii::$app->db->createCommand("select * from bill_card where id={$object_id}")->queryOne();
                $text = "{$name} {$res["code"]} {$res["value"]}";
                break;

            case "down_catalog":
            case "back_down_catalog":
                $res = \Yii::$app->db->createCommand("select * from bill_catalog where id={$object_id}")->queryOne();
                $text = "{$name} \"{$res["name"]}\"";
                break;

            case "down_auction":
            case "down_spec":
                $res = \Yii::$app->db->createCommand("select * from catalog where id={$object_id}")->queryOne();
                $text = "{$name} \"{$res["name"]}\"";
                break;

            case 'section_activate':
            case 'section_deactivate':
                $res = \Yii::$app->db->createCommand("select * from bill_catalog where id={$object_id}")->queryOne();
                $text = "{$name} \"{$res["name"]}\"";
                break;

            case "down_posms":
                return "{$name} ({$object_id} единиц)";
                break;

            default:
                $text = "{$name}";
        }
        return $text;
    }

}