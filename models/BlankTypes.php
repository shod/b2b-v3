<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "blank_types".
 *
 * @property int $id
 * @property string $name
 * @property double $sum
 * @property int $count_day
 * @property string $seller_type
 * @property int $hidden
 * @property int $add_promise
 */
class BlankTypes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'blank_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['sum'], 'number'],
            [['count_day', 'hidden', 'add_promise'], 'integer'],
            [['name', 'seller_type'], 'string', 'max' => 255],
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
            'sum' => 'Sum',
            'count_day' => 'Count Day',
            'seller_type' => 'Seller Type',
            'hidden' => 'Hidden',
            'add_promise' => 'Add Promise',
        ];
    }
}
