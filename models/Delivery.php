<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "delivery".
 *
 * @property int $id
 * @property int $seller_id
 * @property int $type_id
 * @property string $description
 * @property string $cost_data
 */
class Delivery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['seller_id'], 'required'],
            [['seller_id', 'type_id'], 'integer'],
            [['cost_data'], 'string'],
            [['description'], 'string', 'max' => 1024],
            [['seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => Seller::className(), 'targetAttribute' => ['seller_id' => 'id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => DeliveryType::className(), 'targetAttribute' => ['type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'seller_id' => 'Seller ID',
            'type_id' => 'Type ID',
            'description' => 'Description',
            'cost_data' => 'Cost Data',
        ];
    }
}
