<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "b2b_news".
 *
 * @property int $id
 * @property string $title
 * @property string $text
 * @property int $hidden
 * @property string $date
 */
class B2bNews extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'b2b_news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text'], 'string'],
            [['hidden'], 'integer'],
            [['date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'text' => 'Text',
            'hidden' => 'Hidden',
            'date' => 'Date',
        ];
    }
}
