<?php
namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\modules\api\components\ApiAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

use app\models\Message;

class MessageController extends Controller { 

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
    
    public function actionIndex($user_id) {
        if ($this->user != null) {
            $historiMessage = Message::find()->where("msg_from=:msg_from and msg_to=:msg_to or msg_from=:msg_to and msg_to=:msg_from", 
                    [":msg_from" => $this->user->id_user, ":msg_to" => $user_id])->limit(100)->orderBy(['date_create' => SORT_DESC])->all();
            if ($historiMessage != null) {
                $resp = null;
                foreach ($historiMessage as $item) {
                    $date = date_create($item->date_create);
                    $timeZone = $date->getTimezone();
                    $resp[] = array(
                        "MsgFrom" => $this->user->id_user == $item->msg_from ?  true : false,
                        "MsgFromId" => $item->msg_from,
                        "MsgToId" => $item->msg_to,
                        "Msg" => $item->text_msg,
                        "DateMsg" => date_format(date_create($item->date_create), "d/m/Y"),
                        "TimeMsg" => date_format(date_create($item->date_create), "h:i"),
                    );
                }
                return array_reverse($resp);
            }
        }
    }
}