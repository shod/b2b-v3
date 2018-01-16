<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "index_product".
 *
 * @property int $id
 * @property string $catalog_name
 * @property string $product_name
 * @property int $product_id
 * @property string $brand_value
 * @property int $brand_id
 * @property int $index_section_id
 * @property string $basic_name
 * @property int $name_length
 * @property int $review_cnt
 * @property string $description
 * @property string $meta_title
 * @property string $meta_keyword
 * @property string $meta_description
 */
class IndexProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'index_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_name', 'description'], 'required'],
            [['product_id', 'brand_id', 'index_section_id', 'name_length', 'review_cnt'], 'integer'],
            [['description'], 'string'],
            [['catalog_name', 'brand_value'], 'string', 'max' => 128],
            [['product_name', 'basic_name'], 'string', 'max' => 250],
            [['meta_title', 'meta_keyword', 'meta_description'], 'string', 'max' => 255],
            [['product_id'], 'unique'],
            [['brand_id'], 'exist', 'skipOnError' => true, 'targetClass' => IndexBrand::className(), 'targetAttribute' => ['brand_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::className(), 'targetAttribute' => ['product_id' => 'id']],
            [['index_section_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sections::className(), 'targetAttribute' => ['index_section_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'catalog_name' => 'Catalog Name',
            'product_name' => 'Product Name',
            'product_id' => 'Product ID',
            'brand_value' => 'Brand Value',
            'brand_id' => 'Brand ID',
            'index_section_id' => 'Index Section ID',
            'basic_name' => 'Basic Name',
            'name_length' => 'Name Length',
            'review_cnt' => 'Review Cnt',
            'description' => 'Description',
            'meta_title' => 'Meta Title',
            'meta_keyword' => 'Meta Keyword',
            'meta_description' => 'Meta Description',
        ];
    }
}
