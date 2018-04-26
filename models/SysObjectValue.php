<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sys_object_value".
 *
 * @property int $id
 * @property string $object_id
 * @property int $object_sub_id
 * @property int $object_type_id
 * @property int $object_property_id
 * @property string $value
 */
class SysObjectValue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_object_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_sub_id', 'object_type_id', 'object_property_id'], 'integer'],
            [['value'], 'string'],
            [['object_id'], 'string', 'max' => 64],
            [['object_id', 'object_property_id', 'object_type_id', 'object_sub_id'], 'unique', 'targetAttribute' => ['object_id', 'object_property_id', 'object_type_id', 'object_sub_id']],
            [['object_property_id'], 'exist', 'skipOnError' => true, 'targetClass' => SysObjectProperty::className(), 'targetAttribute' => ['object_property_id' => 'id']],
            [['object_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => SysObjectType::className(), 'targetAttribute' => ['object_type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'object_id' => 'Object ID',
            'object_sub_id' => 'Object Sub ID',
            'object_type_id' => 'Object Type ID',
            'object_property_id' => 'Object Property ID',
            'value' => 'Value',
        ];
    }
}
