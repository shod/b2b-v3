<?php

namespace app\modules\billing\models;

use Yii;

/**
 * This is the model class for table "bill_card".
 *
 * @property int $id
 * @property string $code
 * @property int $value
 * @property int $active
 * @property int $bonus
 * @property string $block_date
 */
class BillCard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bill_card';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'value'], 'required'],
            [['id', 'value', 'active', 'bonus'], 'integer'],
            [['block_date'], 'safe'],
            [['code'], 'string', 'max' => 12],
            [['code'], 'unique'],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'value' => 'Value',
            'active' => 'Active',
            'bonus' => 'Bonus',
            'block_date' => 'Block Date',
        ];
    }
}
