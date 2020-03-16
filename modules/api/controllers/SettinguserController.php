<?php
namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\modules\api\components\ApiAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

use app\models\Message;
use app\models\Schedule;
use app\models\SettingUser;
use app\models\ProfileUser;
use app\models\User;
use app\models\Frends;

class SettinguserController extends Controller {

    private $user;

    public function behaviors() {
        $this->user = ApiAuth::authenticate();
        LogsController::logAction($this->user);
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                    'update' => ['post'],
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
        $settingUser = SettingUser::findOne(["id_user" => $this->user->id_user]);
        $resp = null;
        if ($settingUser != null) {
            $resp = array(
                'Industry' => $settingUser->industry,
                'RevenueBegin' => $settingUser->revenue_begin,
                'RevenueEnd' => $settingUser->revenue_end,
                'CostBegin' => $settingUser->cost_begin,
                'CostEnd' => $settingUser->cost_end,
                'HideCompanyNearMe' => $settingUser->hide_company_near_me,
                'HideCompanyNotNearMe' => $settingUser->hide_company_not_near_me,
            );
            
        }
        return $resp;
    }
    
    public function actionUpdate() {
        $params = Yii::$app->request->getBodyParams();
        
        $data = array(
            'id_user' => $this->user->id_user,
            'industry' => $params['Industry'],
            'revenue_begin' => $params['RevenueBegin'],
            'revenue_end' => $params['RevenueEnd'],
            'cost_begin' => $params['CostBegin'],
            'cost_end' => $params['CostEnd'],
            'hide_company_near_me' => $params['HideCompanyNearMe'],
            'hide_company_not_near_me' => $params['HideCompanyNotNearMe'],
        );
            
        $settingUser = SettingUser::findOne(["id_user" => $data['id_user']]);
        if ($settingUser != null) {
            $settingUser->attributes = $data;
            if (!$settingUser->save()) {
                return ['success' => 101, 'massage' => $settingUser->error];
            }
            else {
                return $settingUser;
            }
        }
        else {
            $newSettingUser = new SettingUser();
            $newSettingUser->attributes = $data;
            if (!$newSettingUser->save()) {
                return ['success' => 101, 'massage' => $newSettingUser->error];
            }
            else {
                return $newSettingUser;
            }
        } 
    }
    
    public function actionDelete() {
        if ($this->user != null) {
            Message::deleteAll("msg_from=:id_user or msg_to=:id_user", [":id_user" => $this->user->id_user]);
            Schedule::deleteAll("id_user=:id_user", [":id_user" => $this->user->id_user]);
            Frends::deleteAll("id_user=:id_user", [":id_user" => $this->user->id_user]);
            $settingUser = SettingUser::findOne(["id_user" => $this->user->id_user]);
            if ($settingUser != null) {
                $settingUser->delete();
            }
            $profileUser = ProfileUser::findOne(["id_user" => $this->user->id_user]);
            if ($profileUser != null) {
                $profileUser->delete();
            }
            $user = User::findOne(["id_user" => $this->user->id_user]);
            if ($user != null) {
                $user->delete();
            }
        }
    }
}