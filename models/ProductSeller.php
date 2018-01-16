<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product_seller".
 *
 * @property int $id
 * @property int $product_id
 * @property int $owner_id
 * @property int $seller_id
 * @property string $cost_us
 * @property double $cost_by
 * @property string $description
 * @property int $popular
 * @property int $active
 * @property int $type
 * @property int $count
 * @property int $start_date
 * @property int $exp_date
 * @property int $f_out
 * @property int $wh_state
 * @property int $clone
 * @property string $link
 * @property string $garant
 * @property string $title
 * @property int $is_del
 * @property string $img_url
 * @property int $prod_sec_id
 * @property string $manufacturer
 * @property string $importer
 * @property string $service
 * @property int $delivery_day
 * @property int $term_use
 * @property int $beznal
 * @property int $credit
 * @property int $rassrochka
 * @property int $delivery_m
 * @property int $delivery_rb
 * @property int $setting_bit
 */
class ProductSeller extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_seller';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'owner_id', 'seller_id', 'popular', 'active', 'type', 'count', 'start_date', 'exp_date', 'f_out', 'wh_state', 'clone', 'is_del', 'prod_sec_id', 'delivery_day', 'term_use', 'beznal', 'credit', 'rassrochka', 'delivery_m', 'delivery_rb', 'setting_bit'], 'integer'],
            [['cost_us', 'cost_by'], 'number'],
            [['description'], 'string'],
            [['setting_bit'], 'required'],
            [['link', 'title', 'img_url', 'manufacturer', 'importer', 'service'], 'string', 'max' => 255],
            [['garant'], 'string', 'max' => 64],
            [['seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => Seller::className(), 'targetAttribute' => ['seller_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'owner_id' => 'Owner ID',
            'seller_id' => 'Seller ID',
            'cost_us' => 'Cost Us',
            'cost_by' => 'Cost By',
            'description' => 'Description',
            'popular' => 'Popular',
            'active' => 'Active',
            'type' => 'Type',
            'count' => 'Count',
            'start_date' => 'Start Date',
            'exp_date' => 'Exp Date',
            'f_out' => 'F Out',
            'wh_state' => 'Wh State',
            'clone' => 'Clone',
            'link' => 'Link',
            'garant' => 'Garant',
            'title' => 'Title',
            'is_del' => 'Is Del',
            'img_url' => 'Img Url',
            'prod_sec_id' => 'Prod Sec ID',
            'manufacturer' => 'Manufacturer',
            'importer' => 'Importer',
            'service' => 'Service',
            'delivery_day' => 'Delivery Day',
            'term_use' => 'Term Use',
            'beznal' => 'Beznal',
            'credit' => 'Credit',
            'rassrochka' => 'Rassrochka',
            'delivery_m' => 'Delivery M',
            'delivery_rb' => 'Delivery Rb',
            'setting_bit' => 'Setting Bit',
        ];
    }
}
