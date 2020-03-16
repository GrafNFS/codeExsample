<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_profile_business".
 *
 * @property int $id_user
 * @property string $display_name
 * @property int $name_hide
 * @property string $photo
 * @property int $id_industry
 * @property int $revenue_begin
 * @property int $revenue_end
 * @property string $opening_year
 * @property int $opening_year_hide
 * @property string $location
 * @property int $location_hide
 * @property string $description
 */
class ProfileBusiness extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_profile_business';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user'], 'required'],
            [['id_user', 'name_hide', 'id_industry', 'revenue_begin', 'revenue_end', 'opening_year_hide', 'location_hide'], 'integer'],
            [['description'], 'string'],
            [['display_name', 'photo', 'location'], 'string', 'max' => 100],
            [['opening_year'], 'string', 'max' => 20],
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
            'display_name' => 'Display Name',
            'name_hide' => 'Name Hide',
            'photo' => 'Photo',
            'id_industry' => 'Id Industry',
            'revenue_begin' => 'Revenue Begin',
            'revenue_end' => 'Revenue End',
            'opening_year' => 'Opening Year',
            'opening_year_hide' => 'Opening Year Hide',
            'location' => 'Location',
            'location_hide' => 'Location Hide',
            'description' => 'Description',
        ];
    }
}
