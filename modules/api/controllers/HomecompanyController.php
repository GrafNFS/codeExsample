<?php
namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\modules\api\components\ApiAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

use app\models\ProfileUser;
use app\models\User;
use app\models\Schedule;
use app\models\Frends;

class HomecompanyController extends Controller {

    private $user;

    public function behaviors() {
        $this->user = ApiAuth::authenticate();
        LogsController::logAction($this->user);
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'types' => ['get'],
                    //'all' => ['get'],
                    //'client' => ['get'],
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
    
    public function actionRequests() {
        $resp = NULL;
        $scheduledAll = Schedule::find()->where(["id_business" => $this->user->id_user, "status_id" => 0])->all();
        if ($scheduledAll != null) {
            foreach ($scheduledAll as $item) {
                $profileUser = ProfileUser::findOne(["id_user" => $item->id_user]);
                $user = User::findOne(['id_user' => $item->id_user]);
                if ($profileUser != null) {
                    $display_name = json_decode($profileUser->display_name);
                    $resp[] = array(
                        'IdSchedule' => $item->id_schedule,
                        'DateSchedule' => $item->date_schedule,
                        'TimeSchedule' => $item->time_schedule,
                        "StatusId" => $item->status_id,
                        'User' => array(
                            'UserId' => $profileUser->id_user,
                            'LastName' => $display_name->last_name,
                            'FirstName' => $display_name->first_name,
                            'PhotoStepOne' => $user->photoStepOne,
                            'PhotoStepTwo' => $user->photoStepTwo,
                            'Location' => $profileUser->location,
                            'Description' => $profileUser->description,
                        ),
                    );
                }
            }
            return $resp;
        }
    }
    
    public function actionScheduled() {
        $resp = NULL;
        $scheduledAll = Schedule::find()->where(["id_business" => $this->user->id_user, "status_id" => 1])->all();
        if ($scheduledAll != null) {
            foreach ($scheduledAll as $item) {
                $profileUser = ProfileUser::findOne(["id_user" => $item->id_user]);
                if ($profileUser != null) {
                    $display_name = json_decode($profileUser->display_name);
                    $frends = Frends::findOne(["id_company" => $this->user->id_user, "id_user" => $item->id_user]);
                    $user = User::findOne(['id_user' => $item->id_user]);
                    $tmp = array(
                        'IdSchedule' => $item->id_schedule,
                        'DateSchedule' => $item->date_schedule,
                        'TimeSchedule' => $item->time_schedule,
                        "StatusId" => $item->status_id,
                        'User' => array(
                            'UserId' => $profileUser->id_user,
                            'LastName' => $display_name->last_name,
                            'FirstName' => $display_name->first_name,
                            'PhotoStepOne' => $user->photoStepOne,
                            'PhotoStepTwo' => $user->photoStepTwo,
                            'Location' => $profileUser->location,
                            'Description' => $profileUser->description
                        ),
                    );
                    if ($frends != null && $frends->statys < 5 ) {
                        $resp[] = $tmp;
                    }
                    if ($frends == null) {
                        $resp[] = $tmp;
                    }
                }
            }
            return $resp;
        }
    }
    
    public function actionContacs() {
        if ($this->user != null) {
            $resp = array();
            $allFrendProspect = Frends::find()->where("id_company=:id_user and statys=1", [":id_user" => $this->user->id_user])->all();
            if ($allFrendProspect != null) {
                foreach ($allFrendProspect as $item) {
                    $profileUser = ProfileUser::findOne(["id_user" => $item->id_user]);
                    $user = User::findOne(['id_user' => $item->id_user]);
                    $display_name = json_decode($profileUser->display_name);
                    $resp[] = array(
                        "IdUser" => $profileUser->id_user,
                        'LastName' => $display_name->last_name,
                        'FirstName' => $display_name->first_name,
                        'PhotoStepOne' => $user->photoStepOne,
                        'PhotoStepTwo' => $user->photoStepTwo,
                        "Location" => $profileUser->location,
                    );
                }
                return $resp;
            }
        }
        else {
            return array("message" => "No user ");
        }
    }
    
    public function actionAccept($id_schedule) {
        $oneSched = Schedule::findOne(["id_schedule" => $id_schedule]);
        $oneSched->status_id = 1;
        if(!$oneSched->save()) {
            return $oneSched->errors;
        }
        else {
            $frends = Frends::findOne(["id_company" => $this->user->id_user, "id_user" => $oneSched->id_user]);
            if ($frends == null) {
                $newfrends = new Frends();
                $newfrends->id_company = $this->user->id_user;
                $newfrends->id_user = $oneSched->id_user;
                $newfrends->statys = 1;
                $newfrends->save();
            }
            elseif ($frends->statys == 0) {
                $frends->statys = 1;
                $frends->save();
            }
        }
    }
    
    public function actionDecline($id_schedule) {
        $oneSched = Schedule::findOne(["id_schedule" => $id_schedule]);
        if(!$oneSched->delete()) {
            return $oneSched->errors;
        }
    }
    
    public function actionScheduled_user($id_user) {
        $resp = NULL;
        $scheduledAll = Schedule::find()->where("id_user=:id_user and status_id>=0 and id_business=:id_business", [":id_business" => $this->user->id_user, ":id_user" => $id_user])->all();
        if ($scheduledAll != null) {
            foreach ($scheduledAll as $item) {
                $profileUser = ProfileUser::findOne(["id_user" => $item->id_user]);
                $display_name = json_decode($profileUser->display_name);
                $user = User::findOne(['id_user' => $item->id_user]);
                $resp[] = array(
                    'IdSchedule' => $item->id_schedule,
                    'DateSchedule' => $item->date_schedule,
                    'TimeSchedule' => $item->time_schedule,
                    "StatusId" => $item->status_id,
                    'User' => array(
                        'UserId' => $profileUser->id_user,
                        'LastName' => $display_name->last_name,
                        'FirstName' => $display_name->first_name,
                        'PhotoStepOne' => $user->photoStepOne,
                        'PhotoStepTwo' => $user->photoStepTwo,
                        'Location' => $profileUser->location,
                        'Description' => $profileUser->description
                    ),
                );
            }
            return $resp;
        }
    }
    
    private function hideUser($hide, $name) {
        if ($hide == 1) {
            return "Hide";
        }
        else {
            return $name;
        }
    }
}