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
use app\models\SettingBusiness;
use app\models\ProfileBusiness;
use app\models\User;
use app\models\Frends;

class SettingcompanyController extends Controller {

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
                    'delete' => ['get'],
                    'setting' => ['post'],
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
        if ($this->user != null) {
            $settingBusiness = SettingBusiness::findOne(["id_user" => $this->user->id_user]);

            if ($settingBusiness != null) {
                $resp = array(
                    'CostOfConnection' => $settingBusiness->cost_of_connection,
                    'DistanceSlider' => $settingBusiness->distance_slider,
                    'ProfileComplete' => $settingBusiness->profile_complite,
                    'DistanceSliderNot' => $settingBusiness->distance_slider_not,
                );
                return $resp;
            }
            else {
                return ['success' => 204, 'massage' => null];
            }
        }
    }
    
    public function actionUpdate() {
        
    }
    
    public function actionDelete() {
        if ($this->user != null) {
            Message::deleteAll("msg_from=:id_user or msg_to=:id_user", [":id_user" => $this->user->id_user]);
            Schedule::deleteAll("id_business=:id_user", [":id_user" => $this->user->id_user]);
            Frends::deleteAll("id_company=:id_user", [":id_user" => $this->user->id_user]);
            $settingBusiness = SettingBusiness::findOne(["id_user" => $this->user->id_user]);
            if ($settingBusiness != null) {
                $settingBusiness->delete();
            }
            $profileBusiness = ProfileBusiness::findOne(["id_user" => $this->user->id_user]);
            if ($profileBusiness != null) {
                $profileBusiness->delete();
            }
            $user = User::findOne(["id_user" => $this->user->id_user]);
            if ($user != null) {
                $user->delete();
            }
        }
    }
    
    public function actionSetting() {
        if ($this->user != null) {
            $params = Yii::$app->request->getBodyParams();
            
            $settingBusiness = SettingBusiness::findOne(["id_user" => $this->user->id_user]);
            if ($settingBusiness != null) {
                $settingBusiness->cost_of_connection = $params['CostOfConnection'];
                $settingBusiness->distance_slider = $params['DistanceSlider'];
                $settingBusiness->profile_complite = $params['ProfileComplete'];
                $settingBusiness->distance_slider_not = $params['DistanceSliderNot'];
                if ($settingBusiness->save()) {
                    return ['code' => 1];
                }
            }
            else {
                $newSetting = new SettingBusiness();
                $newSetting->id_user = $this->user->id_user;
                $newSetting->cost_of_connection = $params['CostOfConnection'];
                $newSetting->distance_slider = $params['DistanceSlider'];
                $newSetting->profile_complite = $params['ProfileComplete'];
                $newSetting->distance_slider_not = $params['DistanceSliderNot'];
                if ($newSetting->save()) {
                    return ['code' => 1];
                }
            }
        }
    }
}