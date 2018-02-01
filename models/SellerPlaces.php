<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "seller_places".
 *
 * @property int $id
 * @property string $city
 * @property string $street
 * @property string $house
 * @property string $flat
 * @property int $seller_id
 * @property int $type
 */
class SellerPlaces extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seller_places';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['seller_id'], 'required'],
            [['seller_id', 'type'], 'integer'],
            [['city', 'street', 'house', 'flat'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'city' => 'City',
            'street' => 'Street',
            'house' => 'House',
            'flat' => 'Flat',
            'seller_id' => 'Seller ID',
            'type' => 'Type',
        ];
    }
}
