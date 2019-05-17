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
                
            );
        }
        elseif (static::$_type_id == 2) {
            
        }
    }
    
    private static function setting_user() {
        
    }
    
    private static function setting_business() {
        
    }
}
