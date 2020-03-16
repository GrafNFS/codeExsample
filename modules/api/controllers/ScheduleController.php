<?php
namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\modules\api\components\ApiAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

use app\models\Schedule;

class ScheduleController extends Controller {

    private $user;

    public function behaviors() {
        $this->user = ApiAuth::authenticate();
        LogsController::logAction($this->user);
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['post'],
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
    
    public function actionCreate() {
        $params = Yii::$app->request->getBodyParams();
        
        if ($this->user != null) {
            foreach ($params['ListScheduleCallJson'] as $item) {
                $newSchedule = new Schedule();
                $date = date_create($item["DateSchedule"]);
                $data = array(
                    'date_schedule' => date_format($date, "Y-m-d"),
                    'time_schedule' => $item["TimeSchedule"],
                    //'policies' => $json["Policies"],
                    'id_user' => $this->user->id_user,
                    'id_business' => $item["IdBusiness"],
                    'status_id' => 0,
                );
                $newSchedule->attributes = $data;
                if (!$newSchedule->save()) {
                    return ["message" => $newSchedule->errors];
                }
            }
        }
        else {
            return ["message" => "No avtarisation"];
        }
    }
    
    public function actionDelete($id_schedule) {
        $oneSched = Schedule::findOne(["id_schedule" => $id_schedule]);
        if(!$oneSched->delete()) {
            return $oneSched->errors;
        }
    }
}