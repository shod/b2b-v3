<?php

namespace app\components\db;

/**
 *
 * @author Shchemelev Evgeny <schevgeny@gmail.com>
 */
abstract class SingletonActiveRecord extends \yii\db\ActiveRecord {

    protected static $instance = null;
    protected static $singleton_class = __CLASS__;
    

    protected static function setInstance($value, $key) {
        return self::$instance[static::getClass()][$key] = $value;
    }
    
    protected static function getInstance($key) {
        return (isset(self::$instance[static::$singleton_class][$key]))?self::$instance[static::$singleton_class][$key]: NULL;
    }
    
    private static function getClass() {
        $implementing_class = static::$singleton_class;
        $original_class = __CLASS__;

        if ($implementing_class === $original_class) {
            throw new \yii\web\ServerErrorHttpException ("You MUST provide a <code>protected static \$singleton_class = __CLASS__;</code> statement in your model_ex-class!");
        }
        
        return $implementing_class;
    }
    
    public function beforeSave($insert) {
        if ($this->getIsNewRecord()) {
            if ($this->hasAttribute('created_at') && !$this->created_at) {
                $this->setAttribute('created_at', time());
            }
        }
        if ($this->hasAttribute('updated_at')) {
            $this->setAttribute('updated_at', time());
        }
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     * @return static ActiveRecord instance matching the condition, or `null` if nothing matches.
     */
    public static function findOne($condition)
    {
        $key = md5( print_r($condition, TRUE) . 'find_one');
        if (null === self::getInstance($key) ) {
            self::setInstance(parent::findByCondition($condition)->one(), $key);
        }
        return self::getInstance($key);
    }

    /**
     * @inheritdoc
     * @return static[] an array of ActiveRecord instances, or an empty array if nothing matches.
     */
    public static function findAll($condition)
    {
        return self::findByCondition($condition)->all();
    }
    
    protected static function findByCondition($condition) {
        $key = md5( print_r($condition, TRUE) );
        
        if (null === self::getInstance($key) ) {
            self::setInstance(parent::findByCondition($condition), $key);
        }
        return self::getInstance($key);
    }
    
    /**
     * @inheritdoc
     * @return ActiveQuery the newly created [[ActiveQuery]] instance.
     */
    public static function find()
    {
        return \Yii::createObject(SingletonActiveQuery::className(), [get_called_class()]);
    }
    
}
