<?php

namespace app\models_ex;

class BillAccount extends  \yii\db\ActiveRecord {

    public function getDayDown() {

        return $this->id;
    }

}