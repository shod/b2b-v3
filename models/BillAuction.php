<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bill_auction".
 *
 * @property int $id
 * @property int $owner_id Владелец             seller, services_users
 * @property int $type_id Вид аукциона
 * @property int $object_id Ссылка на запись - источник             catalog
 * @property string $cost Стоимость ставки
 * @property string $cost_auto
 * @property string $date
 * @property int $place_old
 * @property int $place
 * @property int $f_notify
 * @property int $f_show show everywhere
 * @property int $f_auto autobudget
 * @property string $usersrc
 */
class BillAuction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bill_auction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner_id', 'type_id', 'cost'], 'required'],
            [['owner_id', 'type_id', 'object_id', 'place_old', 'place', 'f_notify', 'f_show', 'f_auto'], 'integer'],
            [['cost', 'cost_auto'], 'number'],
            [['date'], 'safe'],
            [['usersrc'], 'string', 'max' => 64],
            [['owner_id', 'object_id', 'type_id'], 'unique', 'targetAttribute' => ['owner_id', 'object_id', 'type_id']],
            [['object_id'], 'exist', 'skipOnError' => true, 'targetClass' => Catalog::className(), 'targetAttribute' => ['object_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'owner_id' => 'Owner ID',
            'type_id' => 'Type ID',
            'object_id' => 'Object ID',
            'cost' => 'Cost',
            'cost_auto' => 'Cost Auto',
            'date' => 'Date',
            'place_old' => 'Place Old',
            'place' => 'Place',
            'f_notify' => 'F Notify',
            'f_show' => 'F Show',
            'f_auto' => 'F Auto',
            'usersrc' => 'Usersrc',
        ];
    }
}
