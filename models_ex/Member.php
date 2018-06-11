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
        if($sys_object_value){
            $sys_object_value->value = $value;
        } else {
            $sys_object_value = new SysObjectValue();
            $sys_object_value->object_id = (string)$id;
            $sys_object_value->object_property_id = $property_id;
            $sys_object_value->object_sub_id = 0;
            $sys_object_value->object_type_id = 7;
            $sys_object_value->value = $value;
            //dd($sys_object_value);
        }
        if(!$sys_object_value->save()){
            dd($sys_object_value->getErrors());
        }
        //$sys_object_value->save();
    }
}