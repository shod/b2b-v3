<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "delivery_link".
 *
 * @property int $delivery_id
 * @property int $delivery_geo_id
 */
class DeliveryLink extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery_link';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['delivery_id', 'delivery_geo_id'], 'required'],
            [['delivery_id', 'delivery_geo_id'], 'integer'],
            [['delivery_id', 'delivery_geo_id'], 'unique', 'targetAttribute' => ['delivery_id', 'delivery_geo_id']],
            [['delivery_id'], 'exist', 'skipOnError' => true, 'targetClass' => Delivery::className(), 'targetAttribute' => ['delivery_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'delivery_id' => 'Delivery ID',
            'delivery_geo_id' => 'Delivery Geo ID',
        ];
    }
}
