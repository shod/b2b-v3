<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "member".
 *
 * @property int $id
 * @property string $login
 * @property string $pwd
 * @property string $name
 * @property int $type
 * @property int $type_reg
 * @property string $email
 * @property int $f_reg_confirm
 */
class Member extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'type_reg', 'f_reg_confirm'], 'integer'],
            [['login', 'pwd', 'name'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 128],
            [['login'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'pwd' => 'Pwd',
            'name' => 'Name',
            'type' => 'Type',
            'type_reg' => 'Type Reg',
            'email' => 'Email',
            'f_reg_confirm' => 'F Reg Confirm',
        ];
    }
}
