<?php
namespace app\modules\api;

use yii\base\Module;
class ApiModule extends Module
{
    public $controllerNamespace = 'app\modules\api\controllers';
    public $defaultController = 'user';
    public function init()
    {
        parent::init();
    }
}
