<?php
namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\modules\api\components\ApiAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

use app\models\City;
use app\models\State;

class StateController extends Controller { 

    private $user;

    public function behaviors() {
        $this->user = ApiAuth::authenticate();
        LogsController::logAction($this->user);
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['post'],
                //'update' => ['put']
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
        $resp = null;
        if ($this->user != null) {
            $allState = State::find()->all();
            foreach ($allState as $item) {
                $resp[] = array(
                    "IdState" => $item->IdState,
                    "State" => $item->State,
                    "Citys" => $allCity = City::find()->where("IdState=:IdState", [":IdState" => $item->IdState])->all()
                );
            }
        }
        return $resp;
    }
    
    
    public function actionUpload() {
        if ($this->user != null) {
            $params = Yii::$app->request->getBodyParams();
            foreach ($params as $item) {
                //return $item;
                $State = State::findOne(["State" => $item["state"]]);
                if ($State != null) {
                    $newCity = new City();
                    $newCity->IdState = $State->IdState;
                    $newCity->City = $item["city"];
                    $newCity->save();
                }
                else {
                    $newState = new State();
                    $newState->State = $item["state"];
                    if ($newState->save()) {
                        $newCity = new City();
                        $newCity->IdState = $newState->IdState;
                        $newCity->City = $item["city"];
                        $newCity->save();
                    }
                }
            }
        }
    }
}