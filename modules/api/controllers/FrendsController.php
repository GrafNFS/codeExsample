<?php
namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\modules\api\components\ApiAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

use app\models\Frends;
use app\models\ProfileBusiness;
use app\models\ProfileUser;
use app\models\User;

class FrendsController extends Controller {

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
    
    public function actionProspect() {
        if ($this->user != null) {
            $resp = null;
            $allFrendProspect = Frends::find()->where("id_user=:id_user and statys=0", [":id_user" => $this->user->id_user])->all();
            if ($allFrendProspect != null) {
                foreach ($allFrendProspect as $item) {
                    $profileCompany = ProfileBusiness::findOne(["id_user" => $item->id_company]);
                    $user = User::findOne(['id_user' => $item->id_company]);
                    if ($profileCompany != null) {
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
            }
            return $resp;
        }
    }
    
    public function actionEstablished() {
        if ($this->user != null) {
            $resp = array();
            $allFrendProspect = Frends::find()->where("id_user=:id_user and statys=1", [":id_user" => $this->user->id_user])->all();
            if ($allFrendProspect != null) {
                foreach ($allFrendProspect as $item) {
                    $profileCompany = ProfileBusiness::findOne(["id_user" => $item->id_company]);
                    $user = User::findOne(['id_user' => $item->id_company]);
                    if ($profileCompany != null) {
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
            }
            return $resp;
        }
    }

    public function actionCreate() {
        if ($this->user != null) {
            $newFrend = new Frends();
            $newFrend->id_user = $this->user->id_user;
            $newFrend->id_company = $_GET['id_company'];
            if ($newFrend->save()) {
                return ['message' => 1];
            }
        }
    }
    
    public function actionDelete($id_company) {
        if ($this->user != null) {
            $Frend = Frends::findOne(["id_company" => $id_company, "id_user" => $this->user->id_user]);
            if ($Frend->delete()) {
                return ['message' => 1];
            }
        }
    }
    
    public function actionInvisible($id_company) {
        if ($this->user != null) {
            $Frend = Frends::findOne(["id_company" => $id_company, "id_user" => $this->user->id_user]);
            if ($Frend != null) {
                $Frend->statys = 6;
                $Frend->save();
            }
            else {
                $newFrend = new Frends();
                $newFrend->id_user = $this->user->id_user;
                $newFrend->id_company = $_GET['id_company'];
                $newFrend->statys = 6;
                $newFrend->save();
            }
        }
    }
    
    public function actionCompdelete($id_user) {
        if ($this->user != null) {
            $Frend = Frends::findOne(["id_company" => $this->user->id_user, "id_user" => $id_user]);
            if ($Frend->delete()) {
                return ['message' => 1];
            }
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
    
    public function actionBlock_user() {
        if ($this->user != null) {
            $resp = array();
            $allFrendProspect = Frends::find()->where("id_company=:id_user and statys=5", [":id_user" => $this->user->id_user])->all();
            
            if ($allFrendProspect != null) {
                foreach ($allFrendProspect as $item) {
                    $profileUser = ProfileUser::findOne(["id_user" => $item->id_user]);
                    $display_name = json_decode($profileUser->display_name);
                    $user = User::findOne(['id_user' => $item->id_user]);
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
    }
    
    public function actionAddblock() {
        if ($this->user != null) {
            $params = Yii::$app->request->getBodyParams();
            foreach ($params['contactCompanies'] as $item) {
                $Frend = Frends::findOne(["id_company" => $this->user->id_user, "id_user" => $item['IdUser']]);
                $Frend->statys = 5;
                $Frend->save();
            }
        }
    }
    
    public function actionUnblock($id_user) {
        if ($this->user != null) {  
            $Frend = Frends::findOne(["id_company" => $this->user->id_user, "id_user" => $id_user]);
            $Frend->statys = 1;
            $Frend->save();
        }
    }
}