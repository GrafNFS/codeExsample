<?php
namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\modules\api\components\ApiAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

use app\models\User;
use app\models\SettingBusiness;

class PaymentController extends Controller {

    private $user;

    public function behaviors() {
        $this->user = ApiAuth::authenticate();
        LogsController::logAction($this->user);
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'addcustomer' => ['post'],
                    'payment' => ['post'],
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
                'source' => $params['Token'],
                'email' => $this->user->email,
            ]);
            $userProfile = User::findOne(['id_user' => $this->user->id_user]);
            $userProfile->cus_stripe_id = $customer->id;
            if ($userProfile->save()) {
                return ["Code" => 1];
            }
            else {
                return ["Code" => 0];
            }
        }
    }
    
    public function actionAddaccount() {
        if ($this->user != null) {
            \Stripe\Stripe::setApiKey("sk_test_mJ2qVM7P1gqT1UjBEyyAWCOW008hVnnBuI");
            try {
                return \Stripe\Account::createLoginLink('acct_1FRyFfJY08Cgoh5e');
            } catch(\Stripe\Error\Card $e) {
                return $e;
            }
        }
    }
    
    public function actionLinkacc() {
        if ($this->user != null) {
            return ["url" => $this->user->link_url];
        }
    }
    
    public function actionPayment() {
        if ($this->user != null) {
            \Stripe\Stripe::setApiKey("sk_test_mJ2qVM7P1gqT1UjBEyyAWCOW008hVnnBuI");
            $params = Yii::$app->request->getBodyParams();
            
            $costOfConnection = SettingBusiness::findOne(["id_user" => $params['id_user']]);
            
            $callSec = round(($params['min'] * 60 + $params['sec']) * ($costOfConnection->cost_of_connection / 60) * 100);
            
            //return $callSec;
            // Создаём оплату 
            try {
                $charge = \Stripe\Charge::create([
                    "amount" => $callSec, // сумма в центах 
                    "currency" => "usd",
                    "customer" => $this->user->cus_stripe_id,
                    "description" => "Call " . $params['min'] . ":" . $params['sec'],
                    "application_fee_amount" => 12,
                    "transfer_data" => [
                        "destination" => "acct_1FRyFfJY08Cgoh5e",
                        //"amount" => $callSec
                    ]
                    
                ]);
                return $charge;
            } catch(\Stripe\Error\Card $e) {
                return $e;
            }
        }
    }
}