<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "delivery_type".
 *
 * @property int $id
 * @property string $name
 * @property int $pos
 * @property int $hidden
 */
class DeliveryType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string'],
            [['pos', 'hidden'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'pos' => 'Pos',
            'hidden' => 'Hidden',
        ];
    }
}
