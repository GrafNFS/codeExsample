<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_profile_user".
 *
 * @property int $id_user
 * @property string $display_name
 * @property string $photo
 * @property string $location
 * @property string $description
 */
class ProfileUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_profile_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user'], 'required'],
            [['id_user'], 'integer'],
            [['description'], 'string'],
            [['display_name'], 'string', 'max' => 200],
            [['photo', 'location'], 'string', 'max' => 100],
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
            'photo' => 'Photo',
            'location' => 'Location',
            'description' => 'Description',
        ];
    }
}
