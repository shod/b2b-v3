<?php

namespace app\modules\billing\models;

use Yii;
use app\models\BillCatalog;

/**
 * This is the model class for table "bill_catalog_seller".
 *
 * @property int $catalog_id
 * @property int $seller_id
 * @property int $f_tarif tarif or views
 * @property string $created_at
 * @property string $day_cost
 *
 * @property BillCatalog $catalog
 */
class BillCatalogSeller extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bill_catalog_seller';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['catalog_id', 'seller_id'], 'required'],
            [['catalog_id', 'seller_id', 'f_tarif'], 'integer'],
            [['created_at'], 'safe'],
            [['day_cost'], 'number'],
            [['seller_id', 'catalog_id'], 'unique', 'targetAttribute' => ['seller_id', 'catalog_id']],
            [['catalog_id', 'seller_id'], 'unique', 'targetAttribute' => ['catalog_id', 'seller_id']],
            [['catalog_id'], 'exist', 'skipOnError' => true, 'targetClass' => BillCatalog::className(), 'targetAttribute' => ['catalog_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'catalog_id' => 'Catalog ID',
            'seller_id' => 'Seller ID',
            'f_tarif' => 'F Tarif',
            'created_at' => 'Created At',
            'day_cost' => 'Day Cost',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalog()
    {
        return $this->hasOne(BillCatalog::className(), ['id' => 'catalog_id']);
    }
}
