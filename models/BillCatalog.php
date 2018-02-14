<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bill_catalog".
 *
 * @property int $id
 * @property string $name
 * @property int $owner_id
 * @property int $hidden
 * @property int $position
 * @property int $cost
 * @property int $f_tarif =1 if package, =0 if section
 * @property int $is_old
 * @property int $f_new
 * @property int $pay_type =0 if day, =1 if per view
 */
class BillCatalog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bill_catalog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['owner_id', 'hidden', 'position', 'cost', 'f_tarif', 'is_old', 'f_new', 'pay_type'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
            'hidden' => 'Hidden',
            'position' => 'Position',
            'cost' => 'Cost',
            'f_tarif' => 'F Tarif',
            'is_old' => 'Is Old',
            'f_new' => 'F New',
            'pay_type' => 'Pay Type',
        ];
    }
}
