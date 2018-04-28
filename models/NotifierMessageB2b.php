<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notifier_message_b2b".
 *
 * @property int $id
 * @property int $seller_id
 * @property string $type
 * @property string $param
 * @property string $tmpl
 * @property int $status
 * @property string $create_date
 */
class NotifierMessageB2b extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notifier_message_b2b';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['seller_id', 'type', 'param', 'tmpl', 'status'], 'required'],
            [['seller_id', 'status'], 'integer'],
            [['create_date'], 'safe'],
            [['type'], 'string', 'max' => 10],
            [['param'], 'string', 'max' => 1024],
            [['tmpl'], 'string', 'max' => 16],
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
            'seller_id' => 'Seller ID',
            'type' => 'Type',
            'param' => 'Param',
            'tmpl' => 'Tmpl',
            'status' => 'Status',
            'create_date' => 'Create Date',
        ];
    }
}
