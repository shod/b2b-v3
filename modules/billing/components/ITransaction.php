<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\billing\components;

/**
 *
 * @author Schemelev E.
 */
interface ITransaction {

    public function process($data = null);
}
