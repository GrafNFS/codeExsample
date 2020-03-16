<?php
namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\modules\api\components\ApiAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

use app\models\ProfileUser;
use app\models\ProfileBusiness;
use app\models\User;

class UsersController extends Controller {

    private $user;

    public function behaviors() {
        $this->user = ApiAuth::authenticate();
        LogsController::logAction($this->user);
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
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
    
    public function actionIndex($id_user) {
        $resp = null;
        if ($this->user != null) {
            $params = Yii::$app->request->getBodyParams();
            
            $userProfile = ProfileUser::findOne(['id_user' => $id_user]);
            $user = User::findOne(['id_user' => $id_user]);
            if ($userProfile != null) {
                $display_name = json_decode($userProfile->display_name);
                $resp = array(
                    'UserId' => $userProfile->id_user,
                    'LastName' => $display_name->last_name,
                    'FirstName' => $display_name->first_name,
                    'PhotoStepOne' => $user->photoStepOne,
                    'PhotoStepTwo' => $user->photoStepTwo,
                    'Location' => $userProfile->location,
                    'Description' => $userProfile->description
                );
            }
        }
        return $resp;
    }
    
    public function actionPhoto_step_one() {
        if ($this->user != null) {
            $parametrs = Yii::$app->request->getBodyParams();
            
            $imgName = 'step_one_user_' . $this->user->id_user . '.jpg';
            UtilitController::base64_to_file($parametrs['PhotoStepOne'], $imgName);
            $imgNameTwo = 'step_two_user_' . $this->user->id_user . '.jpg';
            UtilitController::base64_to_file($parametrs['PhotoStepTwo'], $imgNameTwo);
            if ($this->user->id_type == 1) {
                $user = User::findOne(['id_user' => $this->user->id_user]);
                $user->photoStepOne = $imgName;
                $user->photoStepTwo = $imgNameTwo;
                if ($user->save()) {
                    $userProfile = ProfileUser::findOne(['id_user' => $this->user->id_user]);
                    $display_name = json_decode($userProfile->display_name);
                    return array(
                            'UserId' => $user->id_user,
                            'LastName' => $display_name->last_name,
                            'FirstName' => $display_name->first_name,
                            'PhotoStepOne' => $user->photoStepOne,
                            'PhotoStepTwo' => $user->photoStepTwo,
                            'Location' => $userProfile->location,
                            'Token' => $userProfile->token,
                            'Description' => $userProfile->description,
                        );
                }
            }
            else {
                $userProfile = ProfileBusiness::findOne(['id_user' => $this->user->id_user]);
                $user = User::findOne(['id_user' => $this->user->id_user]);
                $user->photoStepOne = $imgName;
                $user->photoStepTwo = $imgNameTwo;
                if ($user->save()) {
                    return array(
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
            }
        }
        else {
            return['success' => 0, 'massage' => "No authorization"];
        }
    }
    
    public function actionDelete_photo() {
        $user = User::findOne(['id_user' => $this->user->id_user]);
        $path = Yii::getAlias("/var/www/avareapp/web/upload/");
        if (file_exists($path . $user->photoStepOne) && file_exists($path . $user->photoStepTwo)) {
            unlink($path . $user->photoStepOne);
            unlink($path . $user->photoStepTwo);
        }
        $user->photoStepOne = null;
        $user->photoStepTwo = null;
        if($user->save()) {
            if ($this->user->id_type == 1) {
                $userProfile = ProfileUser::findOne(['id_user' => $this->user->id_user]);
                $display_name = json_decode($userProfile->display_name);
                return array(
                        'UserId' => $user->id_user,
                        'LastName' => $display_name->last_name,
                        'FirstName' => $display_name->first_name,
                        'PhotoStepOne' => $user->photoStepOne,
                        'PhotoStepTwo' => $user->photoStepTwo,
                        'Location' => $userProfile->location,
                        'Token' => $userProfile->token,
                        'Description' => $userProfile->description,
                    );
            }
            else {
                $userProfile = ProfileBusiness::findOne(['id_user' => $this->user->id_user]);
                return array(
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
        }
    }
}