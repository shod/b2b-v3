<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bill_transaction".
 *
 * @property int $id
 * @property string $type
 * @property int $account_id
 * @property string $value
 * @property int $object_id
 * @property string $balance_before
 * @property string $date_begin
 * @property string $date_end
 * @property string $error
 * @property int $owner_id
 * @property int $user_id
 * @property string $comment
 */
class BillTransaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bill_transaction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'account_id', 'value', 'balance_before', 'date_begin', 'user_id'], 'required'],
            [['account_id', 'object_id', 'owner_id', 'user_id'], 'integer'],
            [['value', 'balance_before'], 'number'],
            [['date_begin', 'date_end'], 'safe'],
            [['type'], 'string', 'max' => 30],
            [['error', 'comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'account_id' => 'Account ID',
            'value' => 'Value',
            'object_id' => 'Object ID',
            'balance_before' => 'Balance Before',
            'date_begin' => 'Date Begin',
            'date_end' => 'Date End',
            'error' => 'Error',
            'owner_id' => 'Owner ID',
            'user_id' => 'User ID',
            'comment' => 'Comment',
        ];
    }
}
