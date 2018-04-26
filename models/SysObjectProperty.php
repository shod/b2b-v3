<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sys_object_property".
 *
 * @property int $id
 * @property int $object_type_id
 * @property string $name
 * @property string $data_type
 * @property string $description
 * @property int $num
 */
class SysObjectProperty extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_object_property';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['object_type_id', 'num'], 'integer'],
            [['name'], 'string', 'max' => 32],
            [['data_type'], 'string', 'max' => 16],
            [['description'], 'string', 'max' => 64],
            [['object_type_id', 'name'], 'unique', 'targetAttribute' => ['object_type_id', 'name']],
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
            'object_type_id' => 'Object Type ID',
            'name' => 'Name',
            'data_type' => 'Data Type',
            'description' => 'Description',
            'num' => 'Num',
        ];
    }
}
