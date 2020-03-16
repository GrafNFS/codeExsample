<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_setting_business".
 *
 * @property int $id_user
 * @property int $profile_complite
 * @property int $distance_slider
 * @property int $cost_of_connection
 * @property int $distance_slider_not
 */
class SettingBusiness extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_setting_business';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user'], 'required'],
            [['id_user', 'profile_complite', 'distance_slider', 'cost_of_connection', 'distance_slider_not'], 'integer'],
            [['id_user'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_user' => 'Id User',
            'profile_complite' => 'Profile Complite',
            'distance_slider' => 'Distance Slider',
            'cost_of_connection' => 'Cost Of Connection',
            'receipt_payment' => 'Receipt Payment',
        ];
    }
}
