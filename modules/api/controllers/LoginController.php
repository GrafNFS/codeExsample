<?php

namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use app\models\User;
use app\models\ProfileBusiness;
use app\models\ProfileUser;
use app\modules\api\components\ApiAuth;

class LoginController extends Controller {

    public $enableCsrfValidation = false;

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['post'],
                    'token' => ['get'],
                ]
            ],
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ]
        ];
    }

    public function actionIndex() {
        $params = Yii::$app->request->getBodyParams();
        //print_r($params);
        $data = array(
            'email' => $params['Email'],
            'token' => $params['AppKey'],
            'photo' => $params['Photo'],
            'location' => $params['Location'],
            'id_type' => $params['TypeId'],
        );
        if ($data['id_type'] == 1) {
            $data['display_name'] = json_encode(array('last_name' => $params['LastName'], 'first_name' => $params['FirstName']));
        } elseif ($data['id_type'] == 2) {
            $data['display_name'] = $params['LastName'] . " " . $params['FirstName'];
        }
        $response = $this->create_user($data);

        return $response;
    }

    private function create_user($data = NULL) {
        $response = null;
        $userChek = User::findOne(['email' => $data['email']]);
        if ($userChek != null) {
            $userChek->token = $data['token'];
            if ($userChek->id_type == $data['id_type']) {
                if ($userChek->save()) {
                    if ($userChek->id_type == 1) {
                        $userProfile = ProfileUser::findOne(['id_user' => $userChek->id_user]);
                        $display_name = json_decode($userProfile['display_name']);
                        $response = array(
                            'UserId' => $userChek->id_user,
                            'LastName' => $display_name->last_name,
                            'FirstName' => $display_name->first_name,
                            'PhotoStepOne' => $userChek->photoStepOne,
                            'PhotoStepTwo' => $userChek->photoStepTwo,
                            'Location' => $data['location'],
                            'Token' => $data['token'],
                            'Description' => $userProfile->description,
                        );
                    } elseif ($userChek->id_type == 2) {
                        $userProfile = ProfileBusiness::findOne(['id_user' => $userChek->id_user]);
                        $response = array(
                            'UserId' => $userChek->id_user,
                            'DisplayName' => $userProfile->display_name,
                            'Email' => $userChek->email,
                            'PhotoStepOne' => $userChek->photoStepOne,
                            'PhotoStepTwo' => $userChek->photoStepTwo,
                            'Location' => $userProfile->location,
                            'Token' => $data['token'],
                            'Description' => $userProfile->description,
                            "RevenueBegin" => $userProfile->revenue_begin,
                            "RevenueEnd" => $userProfile->revenue_end,
                            "Industry" => $userProfile->id_industry,
                            "OpeningYear" => $userProfile->opening_year,
                            "Description" => $userProfile->description,
                            "NameHide" => $userProfile->name_hide,
                            "OpeningYearHide" => $userProfile->opening_year_hide,
                            "LocationHide" => $userProfile->location_hide,
                            "Url" => $userChek->link_url
                        );
                    }
                } else {
                    //return ['success' => 103, 'massage' => $userChek->error];
                    return null;
                }
            }
            else {
                return null;
            }
        } 
        else {
            $user = new User();
            //$user_transaction = $user->dbConnection->beginTransaction();
            $user_data = array(
                'email' => $data['email'],
                'token' => $data['token'],
                'id_type' => $data['id_type'],
                'last_active' => date("Y-m-d H:i:s"),
                'created' => date("Y-m-d H:i:s"),
            );
            $user->attributes = $user_data;
            if (!$user->save()) {
                //return ['success' => 101, 'massage' => $user->error];
                return null;
            } else {
                if ($user->id_type == 1) {
                    $user_profile = new ProfileUser();
                } elseif ($user->id_type == 2) {
                    $user_profile = new ProfileBusiness();
                }
                $user_data_detail = array(
                    'id_user' => $user->id_user,
                    'photo' => $data['photo'],
                    'display_name' => $data['display_name'],
                    'location' => $data['location'],
                );

                $user_profile->attributes = $user_data_detail;

                if (!$user_profile->save()) {
                    //$user_transaction->rollback();
                    //static::_send_resp(null, 101, $user_profile->error);
                    return null;
                } else {
                    //$user_transaction->commit();
                    if ($user->id_type == 1) {
                        $display_name = json_decode($user_profile['display_name']);
                        $response = array(
                            'UserId' => $user->id_user,
                            'LastName' => $display_name->last_name,
                            'FirstName' => $display_name->first_name,
                            'PhotoStepOne' => $user->photoStepOne,
                            'PhotoStepTwo' => $user->photoStepTwo,
                            'Location' => $data['location'],
                            'Token' => $data['token'],
                            'Description' => $user_profile->description,
                        );
                    } elseif ($user->id_type == 2) {
                        $response = array(
                            'UserId' => $user->id_user,
                            'DisplayName' => $user_profile->display_name,
                            'Email' => $user->email,
                            'PhotoStepOne' => $user->photoStepOne,
                            'PhotoStepTwo' => $user->photoStepTwo,
                            'Location' => $user_profile->location,
                            'Token' => $data['token'],
                            'Description' => $user_profile->description,
                            "RevenueBegin" => $user_profile->revenue_begin,
                            "RevenueEnd" => $user_profile->revenue_end,
                            "Industry" => 0,
                            "OpeningYear" => $user_profile->opening_year,
                            "Description" => $user_profile->description,
                            "NameHide" => 0,
                            "OpeningYearHide" => 0,
                            "LocationHide" => 0,
                            "Url" => $user->link_url
                        );
                    }
                }
            }
        }
        return $response;
    }
    
    public function actionToken () {
        $user = ApiAuth::authenticate();
        $response = null;
        
        if ($user != null) {
            if ($user->id_type == 1) {
                $userProfile = ProfileUser::findOne(['id_user' => $user->id_user]);
                $display_name = json_decode($userProfile->display_name);
                $response = array(
                    'UserId' => $user->id_user,
                    'LastName' => $display_name->last_name,
                    'FirstName' => $display_name->first_name,
                    'PhotoStepOne' => $user->photoStepOne,
                    'PhotoStepTwo' => $user->photoStepTwo,
                    'Location' => $userProfile->location,
                    'Token' => $user->token,
                    'Description' => $userProfile->description,
                );
            } 
            elseif ($user->id_type == 2) {
                $userProfile = ProfileBusiness::findOne(['id_user' => $user->id_user]);
                $response = array(
                    'UserId' => $user->id_user,
                    'DisplayName' => $userProfile->display_name,
                    'Email' => $user->email,
                    'PhotoStepOne' => $user->photoStepOne,
                    'PhotoStepTwo' => $user->photoStepTwo,
                    'Location' => $userProfile->location,
                    'Token' => $user->token,
                    'Description' => $userProfile->description,
                    "RevenueBegin" => $userProfile->revenue_begin,
                    "RevenueEnd" => $userProfile->revenue_end,
                    "Industry" => $userProfile->id_industry,
                    "OpeningYear" => $userProfile->opening_year,
                    "Description" => $userProfile->description,
                    "NameHide" => $userProfile->name_hide,
                    "OpeningYearHide" => $userProfile->opening_year_hide,
                    "LocationHide" => $userProfile->location_hide,
                    "Url" => $user->link_url
                );
            }
            return $response;
        }
        
        return array("message" => "No user");
    }

}
