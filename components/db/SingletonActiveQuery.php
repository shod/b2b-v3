<?php

namespace app\components\db;

/**
 *
 * @author Shchemelev Evgeny <schevgeny@gmail.com>
 */
class SingletonActiveQuery extends \yii\db\ActiveQuery {

    private static $instance = null;
    
    public $duration = null;
    
    public function cache($duration) {
        $this->duration = $duration;
        return $this;
    }
    
    public function one($db = null) {
        $key = md5( print_r($this->createCommand($db), TRUE) );
   
        if (null === self::$instance || !isset(self::$instance[$key]) ) {
            self::$instance[$key] = $this->cache_one($db);
        }
        return self::$instance[$key];
    }
    
    public function all($db = null){
        if($this->duration){
            return \Yii::$app->db->cache(function ($db) {
                return parent::all($db);
            }, $this->duration);
        }
        return parent::all($db);
    }
    
    private function cache_one($db = null){
        if($this->duration){
            return \Yii::$app->db->cache(function ($db) {
                return parent::one($db);
            }, $this->duration);
        }
        return parent::one($db);
    }
   
}
