<?php
namespace app\commands;

//use app\daemons\EchoServer;
use yii\console\Controller;
use Ratchet\App;
use app\daemons\MyChat;

class ServerController extends Controller
{
    public function actionChat()
    {
        $app = new App('appserv.247avare.com', 10000, "0.0.0.0");
        $app->route('/chat', new MyChat, ['*']);
        $app->run();
    }
}