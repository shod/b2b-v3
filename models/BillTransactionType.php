<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bill_transaction_type".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $position
 * @property int $hide
 */
class BillTransactionType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bill_transaction_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['position', 'hide'], 'integer'],
            [['code'], 'string', 'max' => 30],
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
            'code' => 'Code',
            'name' => 'Name',
            'position' => 'Position',
            'hide' => 'Hide',
        ];
    }
}
