<?php

namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\modules\api\components\ApiAuth;
use yii\filters\ContentNegotiator;
use yii\web\Response;

use app\models\Industry;

class IndustryController extends Controller {

    private $user;

    public function behaviors() {
        $this->user = ApiAuth::authenticate();
        LogsController::logAction($this->user);
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ]
        ];
    }

    public function actionIndex() {
        $industryAll = Industry::find()->all();
        if (isset($industryAll) && $industryAll != null) {
            return $industryAll;
        }
        else {
            return ['success' => 204, 'massage' => "No records"];
        }
    }
}
