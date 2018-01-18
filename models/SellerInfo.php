<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "seller_info".
 *
 * @property int $seller_id
 * @property int $b2b_f_nal 0=beznal, 1=nal
 * @property int $b2b_karma
 * @property string $b2b_description
 * @property string $price_url url for rmp
 * @property string $po_phone
 * @property string $po_email
 * @property int $po_balance
 * @property int $po_active
 * @property string $offer_default_desc
 * @property string $img_documents
 * @property string $importers
 * @property string $service_centers
 * @property int $f_b2b_description
 * @property int $f_allow_download
 * @property string $review_comment
 * @property int $refund
 * @property int $certificate
 * @property int $passport
 * @property string $contract_number
 * @property int $contract_date
 * @property string $email_error
 * @property int $promise_delivery
 * @property string $img_registration
 * @property int $f_registration
 * @property int $f_sendpost
 * @property string $currency_rate_perc
 * @property int $f_correct_curs
 * @property int $cost_round_num
 * @property int $f_esf
 * @property int $f_check_img_documents
 */
class SellerInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seller_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['seller_id'], 'required'],
            [['seller_id', 'b2b_f_nal', 'b2b_karma', 'po_balance', 'po_active', 'f_b2b_description', 'f_allow_download', 'refund', 'certificate', 'passport', 'contract_date', 'promise_delivery', 'f_registration', 'f_sendpost', 'f_correct_curs', 'cost_round_num', 'f_esf', 'f_check_img_documents'], 'integer'],
            [['offer_default_desc', 'img_documents', 'importers', 'service_centers', 'review_comment'], 'string'],
            [['currency_rate_perc'], 'number'],
            [['b2b_description', 'price_url', 'po_email', 'email_error', 'img_registration'], 'string', 'max' => 255],
            [['po_phone', 'contract_number'], 'string', 'max' => 32],
            [['seller_id'], 'unique'],
            [['seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => Seller::className(), 'targetAttribute' => ['seller_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'seller_id' => 'Seller ID',
            'b2b_f_nal' => 'B2b F Nal',
            'b2b_karma' => 'B2b Karma',
            'b2b_description' => 'B2b Description',
            'price_url' => 'Price Url',
            'po_phone' => 'Po Phone',
            'po_email' => 'Po Email',
            'po_balance' => 'Po Balance',
            'po_active' => 'Po Active',
            'offer_default_desc' => 'Offer Default Desc',
            'img_documents' => 'Img Documents',
            'importers' => 'Importers',
            'service_centers' => 'Service Centers',
            'f_b2b_description' => 'F B2b Description',
            'f_allow_download' => 'F Allow Download',
            'review_comment' => 'Review Comment',
            'refund' => 'Refund',
            'certificate' => 'Certificate',
            'passport' => 'Passport',
            'contract_number' => 'Contract Number',
            'contract_date' => 'Contract Date',
            'email_error' => 'Email Error',
            'promise_delivery' => 'Promise Delivery',
            'img_registration' => 'Img Registration',
            'f_registration' => 'F Registration',
            'f_sendpost' => 'F Sendpost',
            'currency_rate_perc' => 'Currency Rate Perc',
            'f_correct_curs' => 'F Correct Curs',
            'cost_round_num' => 'Cost Round Num',
            'f_esf' => 'F Esf',
            'f_check_img_documents' => 'F Check Img Documents',
        ];
    }
}
