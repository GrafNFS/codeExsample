<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_setting_user".
 *
 * @property int $id_user
 * @property string $industry
 * @property string $revenue_begin
 * @property string $revenue_end
 * @property string $cost_begin
 * @property string $cost_end
 * @property int $hide_company_near_me
 * @property int $hide_company_not_near_me
 */
class SettingUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_setting_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user'], 'required'],
            [['id_user', 'hide_company_near_me', 'hide_company_not_near_me'], 'integer'],
            [['industry'], 'string', 'max' => 50],
            [['revenue_begin', 'revenue_end', 'cost_begin', 'cost_end'], 'string', 'max' => 45],
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
            'industry' => 'Industry',
            'revenue_begin' => 'Revenue Begin',
            'revenue_end' => 'Revenue End',
            'cost_begin' => 'Cost Begin',
            'cost_end' => 'Cost End',
            'hide_company_near_me' => 'Hide Company Near Me',
            'hide_company_not_near_me' => 'Hide Company Not Near Me',
        ];
    }
}
