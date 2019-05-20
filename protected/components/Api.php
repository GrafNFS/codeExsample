<?php

Class Api extends ApiBase {

    public static function account_login() {

        $model = new User;
        ///print_r($_POST);
        $email = mb_strtolower(Yii::app()->getRequest()->getPost('email'));
        $password = Yii::app()->getRequest()->getPost('password');

        $model->attributes = array('email' => $email, 'password' => $password);

        $validator = new CEmailValidator;
        $error = null;
        $user = $model->check_login($email, $password);
        if (!$validator->validateValue($email) || !$user) {
            static::_send_resp(null, 201, 'Wrong username or password');
        }
        if ($user) {
            $id = $user['id'];
            if ($model->updateByPk($id, array('last_active' => date('Y-m-d H:i:s')))) {
                $userProfile = UserProfile::model()->findByAttributes(array('user_id' => $id));
                $resp = array(
                    'id' => $id,
                    'email' => $user->email,
                    'password' => $user->password,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'country' => $userProfile->country,
                    'city' => $userProfile->city,
                    'street' => $userProfile->street,
                    'zipccode' => $userProfile->zipcode,
                    'phone' => $userProfile->phone,
                    'latitude' => $userProfile->latitude,
                    'longitude' => $userProfile->longitude
                );

                static::_send_resp($resp);
            }
        }
        static::_send_resp(null, 100, 'Unknown error');
    }
    
    public static function home() {
        static::_check_auth();
        
        if (static::$_type_id == 1) {
            $sheduleList = Schedule::model()->findAll("id_user=:id_user", array(":id_user" => static::$_user_id));
        }
        elseif (static::$_type_id == 2) {
            $sheduleList = Schedule::model()->findAll("id_business=:id_business", array(":id_business" => static::$_user_id));
        }
        
        if (isset($sheduleList) && $sheduleList != null) {
            static::_send_resp($sheduleList);
        }
        static::_send_resp(null, 100, 'Unknown error');
    }
    
    public static function create_schedule() {
        static::_check_auth();
        
        $newSchedule = new Schedule();
        $data = array(
            'date_schedule' => Yii::app()->getRequest()->getPost('date_schedule'),
            'time_schedule' => Yii::app()->getRequest()->getPost('time_schedule'),
            'policies' => Yii::app()->getRequest()->getPost('policies'),
            'id_user' => static::$_user_id,
            'id_business' => Yii::app()->getRequest()->getPost('id_business'),
        );
        $newSchedule->attributes = $data;
        if (!$newSchedule->save()) {
            static::_send_resp(null, 101, $newSchedule->getErrors());
        }
        else {
            static::_send_resp($newSchedule);
        }
    }
    
    public static function search_business() {
        $resp = "";
        $subIndustry = Yii::app()->getRequest()->getPost('id_sub_industry');
        $beginRevenue = Yii::app()->getRequest()->getPost('begin_revenue');
        $endRevenue = Yii::app()->getRequest()->getPost('end_revenue');
        
        $searchBusiness = ProfileBusiness::model()->findAll("id_sub_industry=:sub_industry and revenue<=:begin_revenue and revenue>=:end_revenue", 
                array(":sub_industry" => $subIndustry, ":begin_revenue" => $beginRevenue, ":end_revenue" => $endRevenue));
        foreach ($searchBusiness as $item) {
            $settingBusiness = SettingBusiness::model()->find("id_user=:id_user", array(":id_user"=>$item->id_user));
            $resp[] = array(
                "id_user" => $item->id_user,
                "photo" => $item->photo,
                "revenue" => $item->revenue,
                "cost_of_connection" => $settingBusiness->cost_of_connection,
                "opening_year" => $item->opening_year,
                "opening_year_hide" => $item->opening_year_hide,
                "discription" =>$item->discription
            );
        }
        
        static::_send_resp($resp);
    }
    
    public static function output_industry() {
        $industryAll = Industry::model()->findAll();
        if (isset($industryAll) && $industryAll != null) {
            static::_send_resp($industryAll);
        }
        else {
            static::_send_resp(null, 204, "No records");
        }
    }
    
    public static function output_sub_industry() {
        $subIndustryAll = SubIndustry::model()->findAll();
        if (isset($subIndustryAll) && $subIndustryAll != null) {
            static::_send_resp($subIndustryAll);
        }
        else {
            static::_send_resp(null, 204, "No records");
        }
    }
}
