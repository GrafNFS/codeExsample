<?php
namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\ContentNegotiator;
use yii\web\Response;

use app\models\Logs;

class LogsController extends Controller {

    public function logAction($user) {
        
        if ($user != null) {
            $newLogs = new Logs();
            $newLogs->userId = $user->id_user;
            $newLogs->datetimeAction = date("Y-m-d H:i:s");
            $newLogs->action = str_replace("/web/index.php/api", "", $_SERVER['REQUEST_URI']);
            $newLogs->metohod = $_SERVER['REQUEST_METHOD'];
            $newLogs->message = "";
            $newLogs->save();
        }
    }
}