<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_schedule".
 *
 * @property int $id_schedule
 * @property string $date_schedule
 * @property string $time_schedule
 * @property int $policies
 * @property int $id_user
 * @property int $id_business
 * @property int $status_id
 */
class Schedule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_schedule';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['policies', 'id_user', 'id_business', 'status_id'], 'integer'],
            [['id_user', 'status_id'], 'required'],
            [['date_schedule'], 'string', 'max' => 50],
            [['time_schedule'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_schedule' => 'Id Schedule',
            'date_schedule' => 'Date Schedule',
            'time_schedule' => 'Time Schedule',
            'policies' => 'Policies',
            'id_user' => 'Id User',
            'id_business' => 'Id Business',
            'status_id' => 'Status ID',
        ];
    }
}
