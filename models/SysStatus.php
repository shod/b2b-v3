<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sys_status".
 *
 * @property int $id
 * @property string $name
 * @property string $value
 * @property string $note
 * @property int $updated_at
 * @property int $setting_bit
 * @property int $time_alive
 * @property int $base_value
 * @property int $error_bit
 */
class SysStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'value', 'updated_at'], 'required'],
            [['updated_at', 'setting_bit', 'time_alive', 'base_value', 'error_bit'], 'integer'],
            [['name', 'value'], 'string', 'max' => 128],
            [['note'], 'string', 'max' => 255],
            [['name'], 'unique'],
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
            'value' => 'Value',
            'note' => 'Note',
            'updated_at' => 'Updated At',
            'setting_bit' => 'Setting Bit',
            'time_alive' => 'Time Alive',
            'base_value' => 'Base Value',
            'error_bit' => 'Error Bit',
        ];
    }
}
