<?php

namespace app\models_ex;

use app\models\SysObjectProperty;
use app\models\SysObjectValue;

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

    public function setMemberProperty($name,$value,$property_id){
        $id = $this->id;
        $sys_object_value = SysObjectValue::find()->where(['object_id' => $id, 'object_property_id' => $property_id])->one();
        $sys_object_value->value = $value;
        $sys_object_value->save();
    }
}