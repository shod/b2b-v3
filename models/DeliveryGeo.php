<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "delivery_geo".
 *
 * @property int $id
 * @property int $owner_id
 * @property string $name
 * @property string $code
 */
class DeliveryGeo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery_geo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner_id'], 'integer'],
            [['name', 'code'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'owner_id' => 'Owner ID',
            'name' => 'Name',
            'code' => 'Code',
        ];
    }
}
