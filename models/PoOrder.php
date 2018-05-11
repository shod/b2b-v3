<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "po_order".
 *
 * @property int $id
 * @property int $po_contact_id
 * @property int $seller_id
 * @property int $product_id
 * @property int $cost_us
 * @property string $created_at Дата создания заказа
 * @property int $status Признак актуальности заказа             Выставляется при подтверждении кода активации в таблице po_code поле active
 * @property string $done_at Дата выполнения заказа. Выставляется продавцом
 * @property int $sms_id Присваивается ID транзакции при отправки sms на номер продавца
 */
class PoOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'po_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['po_contact_id', 'seller_id', 'product_id', 'cost_us', 'created_at'], 'required'],
            [['po_contact_id', 'seller_id', 'product_id', 'cost_us', 'status', 'sms_id'], 'integer'],
            [['created_at', 'done_at'], 'safe'],
            [['po_contact_id', 'seller_id', 'product_id', 'cost_us', 'created_at'], 'unique', 'targetAttribute' => ['po_contact_id', 'seller_id', 'product_id', 'cost_us', 'created_at']],
            [['po_contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => PoContact::className(), 'targetAttribute' => ['po_contact_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'po_contact_id' => 'Po Contact ID',
            'seller_id' => 'Seller ID',
            'product_id' => 'Product ID',
            'cost_us' => 'Cost Us',
            'created_at' => 'Created At',
            'status' => 'Status',
            'done_at' => 'Done At',
            'sms_id' => 'Sms ID',
        ];
    }
}
