<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_users".
 *
 * @property int $id_user
 * @property string $email
 * @property string $token
 * @property int $id_type
 * @property string $last_active
 * @property string $created
 * @property string $acc_stripe_id
 * @property string $cus_stripe_id
 * @property string $link_url
 * @property string $photoStepOne
 * @property string $photoStepTwo
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_type'], 'integer'],
            [['last_active', 'created'], 'required'],
            [['last_active', 'created'], 'safe'],
            [['email'], 'string', 'max' => 50],
            [['cus_stripe_id', 'link_url', 'photoStepOne', 'photoStepTwo'], 'string', 'max' => 100],
            [['token'], 'string', 'max' => 255],
            [['email'], 'unique'],
        ];
    }
    
    public static function findIdentityByAccessToken($token) {
        $user = static::findOne(['token' => $token]);
        if ($user /* && $user->token_expiry > time() */) {
            // TBD : update access_token
            return $user;
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_user' => 'Id User',
            'email' => 'Email',
            'token' => 'Token',
            'id_type' => 'Id Type',
            'last_active' => 'Last Active',
            'created' => 'Created',
        ];
    }
}
