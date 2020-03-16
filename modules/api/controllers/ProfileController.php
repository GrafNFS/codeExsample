<?php
namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\modules\api\components\ApiAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

use app\models\ProfileBusiness;
use app\models\ProfileUser;

class ProfileController extends Controller {

    private $user;

    public function behaviors() {
        $this->user = ApiAuth::authenticate();
        LogsController::logAction($this->user);
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'user' => ['post'],
                    'company' => ['post'],
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
    
    public function actionUser() {
        if ($this->user != null) {
            $params = Yii::$app->request->getBodyParams();
            
            $userProfile = ProfileUser::findOne(['id_user' => $this->user->id_user]);

            $userProfile->display_name = json_encode(array('last_name' => $params['LastName'], 'first_name' => $params['FirstName']));
            $userProfile->location = $params['Location'];
            $userProfile->description = $params['Description'];
            if ($userProfile->save()) {
                return $userProfile;
            }
        }
    }
    
    public function actionCompany() {
        if ($this->user != null) {
            $params = Yii::$app->request->getBodyParams();
            
            $userProfile = ProfileBusiness::findOne(['id_user' => $this->user->id_user]);

            $userProfile->display_name = $params['DisplayName'];
            $userProfile->location = $params['Location'];
            $userProfile->opening_year = $params['OpeningYear'];
            $userProfile->description = $params['Description'];
            $userProfile->id_industry = (int)$params['Industry'];
            $userProfile->revenue_begin = $params['RevenueBegin'];
            $userProfile->revenue_end = $params['RevenueEnd'];
            $userProfile->name_hide = $params['NameHide'];
            $userProfile->opening_year_hide = $params['OpeningYearHide'];
            $userProfile->location_hide = $params['LocationHide'];

            if ($userProfile->save()) {
                return $userProfile;
            }
        }
    }
}