<?php

namespace app\modules\billing\components;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Obj {

    protected $name;
    protected $id;

    function init($name, $id) {
        $this->name = $name;
        $this->id = $id;
    }

    public function get_object_name() {
        return $this->name;
    }

}
