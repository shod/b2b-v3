<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "seller".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property double $popular
 * @property int $type
 * @property int $member_id
 * @property int $active
 * @property int $f_deleted
 * @property string $date_act
 * @property string $date_deact
 * @property string $phone
 * @property string $email
 * @property string $email_sms
 * @property string $email_auction
 * @property int $f_email_auction
 * @property int $f_notify 1=general, 2=balance3, 4=balance, 8=deact, 16=au_balance, 32=au_place
 * @property int $f_notified look f_notify
 * @property string $work_time
 * @property string $delivery
 * @property string $sell_condition
 * @property string $site
 * @property string $icq
 * @property string $address
 * @property string $delivery_geo
 * @property string $phone_order
 * @property int $weektime
 * @property int $pay_bit
 * @property int $delivery_bit
 * @property int $setting_bit
 * @property string $setting_data
 * @property string $onliner_id ID on onliner, for price import
 * @property int $user_id ответственный
 * @property string $update_type тип обновления цен
 * @property int $bill_account_id
 * @property int $f_beznal
 * @property int $f_credit
 * @property int $f_rassrochka
 * @property int $f_nal
 * @property int $f_auto самовывоз
 * @property int $f_post post delivery
 * @property int $f_notify_zayavka
 * @property int $f_notify_auction_balance
 * @property int $f_notify_auction_place
 * @property int $f_notified_auction_balance
 * @property string $skype
 * @property string $date_start
 * @property string $alias
 * @property int $cnt_reviews
 * @property int $owner_id
 * @property string $register_date
 * @property int $f_offerta
 * @property int $payment_mode_bit
 * @property string $pay_type
 */
class Seller extends \app\models_ex\Seller
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seller';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'phone', 'work_time', 'delivery', 'sell_condition', 'delivery_geo', 'phone_order', 'update_type', 'pay_type'], 'string'],
            [['popular'], 'number'],
            [['type', 'member_id', 'active', 'f_deleted', 'f_email_auction', 'f_notify', 'f_notified', 'weektime', 'pay_bit', 'delivery_bit', 'setting_bit', 'user_id', 'bill_account_id', 'f_beznal', 'f_credit', 'f_rassrochka', 'f_nal', 'f_auto', 'f_post', 'f_notify_zayavka', 'f_notify_auction_balance', 'f_notify_auction_place', 'f_notified_auction_balance', 'cnt_reviews', 'owner_id', 'f_offerta', 'payment_mode_bit'], 'integer'],
            [['date_act', 'date_deact', 'date_start'], 'safe'],
            [['name', 'email', 'email_sms', 'email_auction'], 'string', 'max' => 128],
            [['site', 'address', 'setting_data', 'alias'], 'string', 'max' => 255],
            [['icq'], 'string', 'max' => 9],
            [['onliner_id'], 'string', 'max' => 10],
            [['skype'], 'string', 'max' => 256],
            [['register_date'], 'string', 'max' => 11],
            [['alias'], 'unique'],
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
            'description' => 'Description',
            'popular' => 'Popular',
            'type' => 'Type',
            'member_id' => 'Member ID',
            'active' => 'Active',
            'f_deleted' => 'F Deleted',
            'date_act' => 'Date Act',
            'date_deact' => 'Date Deact',
            'phone' => 'Phone',
            'email' => 'Email',
            'email_sms' => 'Email Sms',
            'email_auction' => 'Email Auction',
            'f_email_auction' => 'F Email Auction',
            'f_notify' => 'F Notify',
            'f_notified' => 'F Notified',
            'work_time' => 'Work Time',
            'delivery' => 'Delivery',
            'sell_condition' => 'Sell Condition',
            'site' => 'Site',
            'icq' => 'Icq',
            'address' => 'Address',
            'delivery_geo' => 'Delivery Geo',
            'phone_order' => 'Phone Order',
            'weektime' => 'Weektime',
            'pay_bit' => 'Pay Bit',
            'delivery_bit' => 'Delivery Bit',
            'setting_bit' => 'Setting Bit',
            'setting_data' => 'Setting Data',
            'onliner_id' => 'Onliner ID',
            'user_id' => 'User ID',
            'update_type' => 'Update Type',
            'bill_account_id' => 'Bill Account ID',
            'f_beznal' => 'F Beznal',
            'f_credit' => 'F Credit',
            'f_rassrochka' => 'F Rassrochka',
            'f_nal' => 'F Nal',
            'f_auto' => 'F Auto',
            'f_post' => 'F Post',
            'f_notify_zayavka' => 'F Notify Zayavka',
            'f_notify_auction_balance' => 'F Notify Auction Balance',
            'f_notify_auction_place' => 'F Notify Auction Place',
            'f_notified_auction_balance' => 'F Notified Auction Balance',
            'skype' => 'Skype',
            'date_start' => 'Date Start',
            'alias' => 'Alias',
            'cnt_reviews' => 'Cnt Reviews',
            'owner_id' => 'Owner ID',
            'register_date' => 'Register Date',
            'f_offerta' => 'F Offerta',
            'payment_mode_bit' => 'Payment Mode Bit',
            'pay_type' => 'Pay Type',
        ];
    }
}
