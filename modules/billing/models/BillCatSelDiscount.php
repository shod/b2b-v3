<?php

namespace app\modules\billing\models;

use Yii;

/**
 * This is the model class for table "bill_cat_sel_discount".
 *
 * @property integer $seller_id
 * @property integer $catalog_id
 * @property string $date_start
 * @property string $date_expired
 */
class BillCatSelDiscount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bill_cat_sel_discount';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['seller_id', 'catalog_id', 'date_start', 'date_expired'], 'required'],
            [['seller_id', 'catalog_id'], 'integer'],
            [['date_start', 'date_expired'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'seller_id' => 'Seller ID',
            'catalog_id' => 'Catalog ID',
            'date_start' => 'Date Start',
            'date_expired' => 'Date Expired',
        ];
    }
}
