<?php
namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\modules\api\components\ApiAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

use app\models\ProfileBusiness;
use app\models\SettingUser;
use app\models\User;
use app\models\SettingBusiness;
use app\models\Schedule;
use app\models\Frends;

class HomeuserController extends Controller {

    private $user;

    public function behaviors() {
        $this->user = ApiAuth::authenticate();
        LogsController::logAction($this->user);
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'popular' => ['get'],
                    'scheduled' => ['get'],
                    'setting' => ['get'],
                    'search' => ['post'],
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
    
    public function actionAddcustomer() {
        if ($this->user != null) {
            \Stripe\Stripe::setApiKey("sk_test_mJ2qVM7P1gqT1UjBEyyAWCOW008hVnnBuI");
            $params = Yii::$app->request->getBodyParams();
            $customer = \Stripe\Customer::create([
                'source' => $params['token'],
                'email' => $this->user->email,
            ]);
            $userProfile = User::findOne(['id_user' => $this->user->id_user]);
            $userProfile->cus_stripe_id = $customer->id;
            if ($userProfile->save()) {
                return ["code" => 1];
            }
            else {
                return ["code" => 0];
            }
        }
    }

    public function actionPopular() {
        $resp = NULL;
        $searchBusiness = "";
        $settingUser = SettingUser::findOne(["id_user" => $this->user->id_user]);
        
        if ($settingUser != null && $settingUser->industry != null) {
            $searchBusiness = ProfileBusiness::find()->where("id_industry=:id_industry", ["id_industry" => $settingUser->industry])->all();
        }
        else {
            $searchBusiness = ProfileBusiness::find()->where("id_industry<>0")->all();
        }
        foreach ($searchBusiness as $item) {
            $settingBusiness = SettingBusiness::findOne(["id_user"=>$item->id_user]);
            $frends = Frends::findOne(["id_company" => $item->id_user, "id_user" => $this->user->id_user]);
            $user = User::findOne(['id_user' => $item->id_user]);
            if ($user != null) {
                $tmp = array(
                    "IdUser" => $item->id_user,
                    "DisplayName" => $this->hideCompany($item->name_hide, $item->display_name),
                    'PhotoStepOne' => $user->photoStepOne,
                    'PhotoStepTwo' => $user->photoStepTwo,
                    "RevenueBegin" => $item->revenue_begin,
                    "RevenueEnd" => $item->revenue_end,
                    "CostOfConnection" => $settingBusiness != null ? $settingBusiness->cost_of_connection : "",
                    "IdIndustry" => $item->id_industry,
                    "OpeningYear" => $item->opening_year,
                    "OpeningYearHide" => $item->opening_year_hide,
                    "Description" => $item->description,
                    "Prospective" => $frends != null ? true : false
                );
                if ($frends != null && $frends->statys < 5) {
                    $resp[] = $tmp;
                }
                if ($frends == null) {
                    $resp[] = $tmp;
                }
            }
        }
        return $resp;
    }
    
    public function actionScheduled() {
        $resp = NULL;
        $scheduledAll = Schedule::find()->where("id_user=:id_user and status_id>0", [":id_user" => $this->user->id_user])->all();
        if ($scheduledAll != null) {
            foreach ($scheduledAll as $item) {
                $profileCompany = ProfileBusiness::findOne(["id_user" => $item->id_business]);
                $frends = Frends::findOne(["id_company" => $item->id_business, "id_user" => $this->user->id_user]);
                $user = User::findOne(['id_user' => $item->id_business]);
                if ($user != null && $profileCompany->id_industry != 0) {
                    $tmp = array(
                        'IdSchedule' => $item->id_schedule,
                        'DateSchedule' => date_format(date_create($item->date_schedule), "d/m/Y"),
                        'TimeSchedule' => $item->time_schedule,
                        "StatusId" => $item->status_id,
                        'Company' => array(
                            'IdUser' => $profileCompany->id_user,
                            'DisplayName' => $this->hideCompany($profileCompany->name_hide, $profileCompany->display_name),
                            'PhotoStepOne' => $user->photoStepOne,
                            'PhotoStepTwo' => $user->photoStepTwo,
                            'IdIndustry' => $profileCompany->id_industry
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
    
    public function actionSearch() {
        if ($this->user != null) {
            $params = Yii::$app->request->getBodyParams();
            $resp = null;
            $industry = $params['Industry'];
            $beginRevenue = $params['RevenueBegin'];
            $endRevenue = $params['RevenueEnd'];

            $searchBusiness = ProfileBusiness::find()->where("revenue_begin>=:revenue_begin and revenue_end<=:revenue_end and id_industry=:id_industry",[":revenue_begin" => $beginRevenue, ":revenue_end" => $endRevenue, ":id_industry" => $industry])->all();
            foreach ($searchBusiness as $item) {
                $settingBusiness = SettingBusiness::findOne(["id_user"=>$item->id_user]);
                $frends = Frends::findOne(["id_company" => $item->id_user, "id_user" => $this->user->id_user]);
                $user = User::findOne(['id_user' => $item->id_user]);
                $tmp = array(
                    "IdUser" => $item->id_user,
                    "IdIndustry" => $item->id_industry,
                    "DisplayName" => $this->hideCompany($item->name_hide, $item->display_name),
                    'PhotoStepOne' => $user->photoStepOne,
                    'PhotoStepTwo' => $user->photoStepTwo,
                    "RevenueBegin" => $item->revenue_begin,
                    "RevenueEnd" => $item->revenue_end,
                    "CostOfConnection" => $settingBusiness != null ? $settingBusiness->cost_of_connection : "",
                    "OpeningYear" => $item->opening_year,
                    "OpeningYearHide" => $item->opening_year_hide,
                    "Description" =>$item->description,
                    "Prospective" => $frends != null ? true : false
                );

                if ($frends != null && $frends->statys < 5) {
                    $resp[] = $tmp;
                }
                if ($frends == null) {
                    $resp[] = $tmp;
                }
            }

            return $resp;
        }
        else {
            return ["error" => "No Avtarization"];
        }
    }
    
    public function actionContacs() {
        $resp = null;
        $scheduledAll = Schedule::find()->where("id_user=:id_user and status_id>0", [":id_user" => $this->user->id_user])->all();
        $tempUser = 0;
        if ($scheduledAll != null) {
            foreach ($scheduledAll as $item) {
                $profileCompany = ProfileBusiness::findOne(["id_user" => $item->id_business]);
                $user = User::findOne(['id_user' => $item->id_user]);
                if ($tempUser != $item->id_business && $profileCompany->id_industry != 0) {
                    $tempUser = $item->id_business;
                    $resp[] = array(
                        "IdUser" => $profileCompany->id_user,
                        "DisplayName" => $this->hideCompany($profileCompany->name_hide, $profileCompany->display_name),
                        'PhotoStepOne' => $user->photoStepOne,
                        'PhotoStepTwo' => $user->photoStepTwo,
                        "RevenueBegin" => $profileCompany->revenue_begin,
                        "RevenueEnd" => $profileCompany->revenue_end,
                        "OpeningYear" => $profileCompany->opening_year,
                        "OpeningYearHide" => $profileCompany->opening_year_hide,
                        "Description" =>$profileCompany->description
                    );
                }
            }
            return $resp;
        }
    }
    
    public function actionScheduled_business() {
        $resp = NULL;
        $scheduledAll = Schedule::find()->where("id_user=:id_user and status_id>0 and id_business=:id_business", [":id_user" => $this->user->id_user, ":id_business" => $_GET['id_business']])->all();
        if ($scheduledAll != null) {
            foreach ($scheduledAll as $item) {
                $profileCompany = ProfileBusiness::findOne(["id_user" => $item->id_business]);
                $user = User::findOne(['id_user' => $item->id_user]);
                if ($user != null && $profileCompany->id_industry != 0) {
                    $resp[] = array(
                        'IdSchedule' => $item->id_schedule,
                        'DateSchedule' => date_format(date_create($item->date_schedule), "d/m/Y"),
                        'TimeSchedule' => $item->time_schedule,
                        "StatusId" => $item->status_id,
                        'Company' => array(
                            'IdUser' => $profileCompany->id_user,
                            'DisplayName' => $this->hideCompany($profileCompany->name_hide, $profileCompany->display_name),
                            'PhotoStepOne' => $user->photoStepOne,
                            'PhotoStepTwo' => $user->photoStepTwo,
                            'IdIndustry' => $profileCompany->id_industry
                        ),
                    );
                }
            }
            return $resp;
        }
    }
    
    private function hideCompany($hide, $name) {
        if ($hide == 1) {
            return "Hide";
        }
        else {
            return $name;
        }
    }
}