<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "review".
 *
 * @property int $id
 * @property int $owner_id
 * @property int $object_id
 * @property string $review
 * @property int $popular
 * @property int $type 1=seller, 2=product, 3=service_user
 * @property string $name
 * @property int $date
 * @property string $contact
 * @property int $state
 * @property string $user_ip
 * @property int $active
 * @property string $exp
 * @property string $plus
 * @property string $minus
 * @property string $title
 * @property string $delivery
 * @property string $cost
 * @property string $conclusion
 * @property int $moderate
 * @property string $user_id
 * @property int $f_plus_minus
 * @property int $product_id
 * @property string $product_name
 * @property int $permit
 * @property int $f_delivery
 * @property int $f_cost
 * @property int $f_conclusion
 * @property int $voice_up
 * @property int $voice_down
 * @property int $f_photo
 * @property string $email
 * @property string $phone
 * @property string $admin_comment
 * @property int $f_s
 * @property int $from_ya
 * @property int $hide_by_ek
 */
class Review extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'review';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner_id', 'object_id', 'popular', 'type', 'date', 'state', 'active', 'moderate', 'user_id', 'f_plus_minus', 'product_id', 'permit', 'f_delivery', 'f_cost', 'f_conclusion', 'voice_up', 'voice_down', 'f_photo', 'f_s', 'from_ya', 'hide_by_ek'], 'integer'],
            [['review', 'name', 'exp', 'title'], 'required'],
            [['review', 'plus', 'minus', 'title', 'admin_comment'], 'string'],
            [['name', 'contact'], 'string', 'max' => 128],
            [['user_ip'], 'string', 'max' => 16],
            [['exp', 'delivery', 'cost'], 'string', 'max' => 30],
            [['conclusion'], 'string', 'max' => 3],
            [['product_name', 'email'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
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
            'object_id' => 'Object ID',
            'review' => 'Review',
            'popular' => 'Popular',
            'type' => 'Type',
            'name' => 'Name',
            'date' => 'Date',
            'contact' => 'Contact',
            'state' => 'State',
            'user_ip' => 'User Ip',
            'active' => 'Active',
            'exp' => 'Exp',
            'plus' => 'Plus',
            'minus' => 'Minus',
            'title' => 'Title',
            'delivery' => 'Delivery',
            'cost' => 'Cost',
            'conclusion' => 'Conclusion',
            'moderate' => 'Moderate',
            'user_id' => 'User ID',
            'f_plus_minus' => 'F Plus Minus',
            'product_id' => 'Product ID',
            'product_name' => 'Product Name',
            'permit' => 'Permit',
            'f_delivery' => 'F Delivery',
            'f_cost' => 'F Cost',
            'f_conclusion' => 'F Conclusion',
            'voice_up' => 'Voice Up',
            'voice_down' => 'Voice Down',
            'f_photo' => 'F Photo',
            'email' => 'Email',
            'phone' => 'Phone',
            'admin_comment' => 'Admin Comment',
            'f_s' => 'F S',
            'from_ya' => 'From Ya',
            'hide_by_ek' => 'Hide By Ek',
        ];
    }
}
