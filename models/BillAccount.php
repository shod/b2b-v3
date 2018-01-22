<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bill_account".
 *
 * @property int $id
 * @property int $owner_id
 * @property string $balance
 * @property int $active
 * @property string $cost_day списывание за день
 * @property string $last_time_auction_email
 * @property string $balance_all
 * @property int $last_time_recalc
 * @property int $balance_clicks
 */
class BillAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bill_account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner_id', 'active', 'last_time_recalc', 'balance_clicks'], 'integer'],
            [['balance', 'cost_day', 'balance_all'], 'number'],
            [['last_time_auction_email'], 'safe'],
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
            'balance' => 'Balance',
            'active' => 'Active',
            'cost_day' => 'Cost Day',
            'last_time_auction_email' => 'Last Time Auction Email',
            'balance_all' => 'Balance All',
            'last_time_recalc' => 'Last Time Recalc',
            'balance_clicks' => 'Balance Clicks',
        ];
    }
}
