<?php

class ApiB extends ApiBase {

    public static function account_create() {
        $data = array(
            'display_name' => Yii::app()->getRequest()->getPost('display_name'),
            'email' => Yii::app()->getRequest()->getPost('email'),
            'token' => Yii::app()->getRequest()->getPost('token'),
            'photo' => Yii::app()->getRequest()->getPost('photo'),
            'location' => new CDbExpression('POINT(:lat, :lon)', array('lat' => 0, 'lon' => 0)),
            'id_type' => Yii::app()->getRequest()->getPost('id_type'),
        );
        $response = static::create_user($data);

        static::_send_resp($response);
    }

    private static function create_user($data = NULL) {
        $user = new User();
        if ($user->check_email_unique($data['email'])) {
            //validation error
            static::_send_resp(null, 102, 'User exist.');
        } else {
            $user_transaction = $user->dbConnection->beginTransaction();
            $user_data = array(
                'email' => $data['email'],
                'token' => $data['token'],
                'id_type' => $data['id_type'],
                'last_active' => new CDbExpression('NOW()'),
                'created' => new CDbExpression('NOW()'),
            );
            $user->attributes = $user_data;
            if (!$user->save()) {
                static::_send_resp(null, 101, $user->getErrors());
            } 
            else {
                if ($user->id_type == 1) {
                    $user_profile = new ProfileUser();
                } 
                elseif ($user->id_type == 2) {
                    $user_profile = new ProfileBusiness();
                }
                $user_data_detail = array(
                    'user_id' => $user->id_user,
                    'photo' => $data['photo'],
                    'display_name' => $data['display_name'],
                    'location' => $data['location'],
                );

                $user_profile->attributes = $user_data_detail;

                if (!$user_profile->save()) {
                    //del user if error validation in user_profile
                    $user_transaction->rollback();
                    //validation error
                    static::_send_resp(null, 101, 'Validation error.');
                    //var_dump($user_profile->getErrors());exit;
                } else {
                    $user_transaction->commit();

                    $response = array(
                        'id' => (int) $user->getPrimaryKey(),
                        'email' => $user_data['email'],
                        'display_name' => $user_data_detail['display_name'],
                        'photo' => $user_data_detail['photo'],
                        'location' => $user_data_detail['location'],
                        'token' => $user_data['token'],
                    );
                }
            }
        }
        return $response;
    }
    
    public static function setting_profile() {
        static::_check_auth();
        
        if (static::$_type_id == 1) {
            $data = array(
                'id_user' => static::$_user_id,
                'default_industry' => Yii::app()->getRequest()->getPost('default_industry'),
                'default_revenue_slider' => Yii::app()->getRequest()->getPost('default_revenue_slider'),
                'connection_cost_slider' => Yii::app()->getRequest()->getPost('connection_cost_slider'),
                'distance_slider' => Yii::app()->getRequest()->getPost('distance_slider'),
            );
            $this->setting_user($data);
        }
        elseif (static::$_type_id == 2) {
            $data = array(
                'id_user' => static::$_user_id,
                'profile_complite' => Yii::app()->getRequest()->getPost('profile_complite'),
                'distance_slider' => Yii::app()->getRequest()->getPost('distance_slider'),
                'cost_of_connection' => Yii::app()->getRequest()->getPost('cost_of_connection'),
                'receipt_payment' => Yii::app()->getRequest()->getPost('receipt_payment'),
            );
            $this->setting_business($data);
        }
    }
    
    private static function setting_user($data) {
        $settingUser = SettingUser::model()->find("id_user=:id_user", array(":id_user" => $data['id_user']));
        if ($settingUser != null) {
            $settingUser->attributes = $data;
            if (!$settingUser->save()) {
                static::_send_resp(null, 101, $settingUser->getErrors());
            }
            else {
                static::_send_resp($settingUser);
            }
        }
        else {
            $newSettingUser = new SettingUser();
            $newSettingUser->attributes = $data;
            if (!$newSettingUser->save()) {
                static::_send_resp(null, 101, $newSettingUser->getErrors());
            }
            else {
                static::_send_resp($newSettingUser);
            }
        } 
    }
    
    private static function setting_business($data) {
        $settingBusiness = SettingBusiness::model()->find("id_user=:id_user", array(":id_user" => $data['id_user']));
        if ($settingBusiness != null) {
            $settingBusiness->attributes = $data;
            if (!$settingBusiness->save()) {
                static::_send_resp(null, 101, $settingBusiness->getErrors());
            }
            else {
                static::_send_resp($settingBusiness);
            }
        }
        else {
            $newSettingBusiness = new SettingBusiness();
            $newSettingBusiness->attributes = $data;
            if (!$newSettingBusiness->save()) {
                static::_send_resp(null, 101, $newSettingBusiness->getErrors());
            }
            else {
                static::_send_resp($newSettingBusiness);
            }
        }
    }
}
