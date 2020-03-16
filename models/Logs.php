<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "logs".
 *
 * @property int $id
 * @property string $datetimeAction
 * @property int $userId
 * @property string $action
 * @property string $metohod
 * @property string $message
 */
class Logs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['datetimeAction', 'userId', 'action', 'metohod'], 'required'],
            [['datetimeAction'], 'safe'],
            [['userId'], 'integer'],
            [['message', 'metohod'], 'string'],
            [['action'], 'string', 'max' => 100],
            [['metohod'], 'string', 'max' => 10],
            [['datetimeAction'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'datetimeAction' => 'Datetime Action',
            'userId' => 'User ID',
            'action' => 'Action',
            'message' => 'Message',
        ];
    }
}
