<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "catalog".
 *
 * @property int $id
 * @property string $name
 * @property int $owner_id
 * @property string $type
 * @property string $catalog_owner
 * @property int $position
 * @property int $hidden
 * @property string $description
 * @property string $template
 * @property int $f_main
 * @property string $keyword
 * @property int $rating
 * @property string $date_start
 * @property int $f_similar
 * @property int $f_like
 * @property int $bin_templates
 * @property int $f_tdefault
 * @property int $setting_bit
 * @property int $setting_bit_pay
 */
class Catalog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'catalog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner_id', 'position', 'hidden', 'f_main', 'rating', 'f_similar', 'f_like', 'bin_templates', 'f_tdefault', 'setting_bit', 'setting_bit_pay'], 'integer'],
            [['description'], 'string'],
            [['date_start'], 'safe'],
            [['name', 'keyword'], 'string', 'max' => 128],
            [['type'], 'string', 'max' => 24],
            [['catalog_owner'], 'string', 'max' => 64],
            [['template'], 'string', 'max' => 255],
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
            'owner_id' => 'Owner ID',
            'type' => 'Type',
            'catalog_owner' => 'Catalog Owner',
            'position' => 'Position',
            'hidden' => 'Hidden',
            'description' => 'Description',
            'template' => 'Template',
            'f_main' => 'F Main',
            'keyword' => 'Keyword',
            'rating' => 'Rating',
            'date_start' => 'Date Start',
            'f_similar' => 'F Similar',
            'f_like' => 'F Like',
            'bin_templates' => 'Bin Templates',
            'f_tdefault' => 'F Tdefault',
            'setting_bit' => 'Setting Bit',
            'setting_bit_pay' => 'Setting Bit Pay',
        ];
    }
}
