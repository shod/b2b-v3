<?php

namespace app\models_ex;

class Member extends  \app\models\Member {

    public function getMemberProperties(){
        $id = $this->id;

        $sql = "SELECT name, value from sys_object_value as sov
        join sys_object_property as sop on (sov.object_property_id = sop.id)
        WHERE sov.object_id = {$id}";

        $data = \Yii::$app->db->createCommand($sql)->queryAll();
        $data_array = [];
        foreach ($data as $d){
            $data_array[$d['name']] = $d['value'];
        }
        return $data_array;
    }
}