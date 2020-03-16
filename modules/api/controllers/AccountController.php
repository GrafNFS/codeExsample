<?php
namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\modules\api\components\ApiAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

use app\models\User;

class AccountController extends Controller {

    private $user;

    public function behaviors() {
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
    
    public function actionIndex() {
        $user = User::findOne(["token" => $_GET['state']]);
        if ($user !=null) {
            \Stripe\Stripe::setApiKey("sk_test_mJ2qVM7P1gqT1UjBEyyAWCOW008hVnnBuI");
            $params=['client_secret'=>'sk_test_mJ2qVM7P1gqT1UjBEyyAWCOW008hVnnBuI', 'code'=>$_GET['code'], 'grant_type'=>"authorization_code"];
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,'https://connect.stripe.com/oauth/token');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $returned = curl_exec($ch);
            curl_close ($ch);
            $user->acc_stripe_id = $returned;
            
            try {
                $json = json_decode($returned);
                $link = \Stripe\Account::createLoginLink($json->stripe_user_id);
                $user->link_url = $link->url;
            } catch(\Stripe\Error\Card $e) {
            }
            
            if($user->save()) {
                header("Location: " + $user->link_url);
            }
            else {
                return $user->errors;
            }
        }
        return $_GET;
    }
}