<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\xj\qrcode\QRcode;
use app\xj\qrcode\widgets\Text;
//use app\xj\qrcode\widgets\Email;
use app\xj\qrcode\widgets\Card;
use app\models\Bags;
use app\models\Orders;
use app\models\BagsOrders;
use app\models\Customers;
use app\models\OrdersTastes;

class QrcodeController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex() {
        //$qr = "asMMlOo,asU3eSa,asyre9N,asTjmQK,asfDsiA,asov11A,asZA9LR,asT76P3,askzrxL,asFY3gY,asPH8Qe,asvS4ql,asoLoB6,asJo2Yp,asNUBLV,asmOgNu,asDIadY,aszqHrd,asy7qny,asjDH7R,asjiaQN,asnVkQK,asHefi8,asusQGJ,asIVNX8,as29z7l,asGno1h,askmp9D,as7wRCY,asT5jtm,asl7quw,ash00V2,ash4wfv,as5Xewl,asl0rrB,as981hl,asw8Sw7,asDhKZu,asaCYJC,aspR9hC,asqdqCi,asr1NPi,asmjgdO,asipcTD,asL1rNO,asYSVmz,as2Sibj,aszF6OM,asMrh4V,as4jiHW,asqktgU,asUT5LA,asr9WlS,asSodJ1,aszgy3p,as5oHy3,asP0jin,asxd9rB,aspWtgI,asPbl86,as7kdiW,asJhenj,as57UtI,as26FG4,as96h4g,asyZg3i,asfZRcU,astL5IQ,aswmNkf,asUMc3w,asp8ihQ,asq1bas,asFpkMH,asUBiUL,asqZAcS,asY2NEF,as6XojB,asIPY9Y,as1omfz,asNYBpp,asmO3wc,asfJl3o,asgSpFj,asqmxFJ,asuNqHL,asDIkwE,as47uLy,aseSUGA,as67wHE,asrzARP,asgFPXm,as9rI4I,asmSWBA,aslRAab,assFh28,as5ROWs,as4PIfY,asWBuAu,asgjXXL,ash5tFi";
        $qr = $this->detailInfoBag($_GET['id_bags']);

        echo Text::widget([
            'outputDir' => '@webroot/upload/qrcode',
            'outputDirWeb' => '@web/upload/qrcode',
            'ecLevel' => QRcode::QR_ECLEVEL_L,
            'text' => json_encode($qr),
            'size' => 6,
        ]);

        //Widget create a Action URL //QR Create by Action
//        Text::widget([
//            'actions' => ['qrcode/index'],
//            'text' => 'aaaa@gmail.com',
//            'size' => 3,
//            'margin' => 4,
//            'ecLevel' => QRcode::QR_ECLEVEL_L,
//        ]);
        //return $this->render('index');
        //echo $this->makeId();
    }
    
    private function detailInfoBag($id_bag) {
        $resp = array();
        $bagOne = Bags::findOne(['id_bags' => $id_bag]);
        $bagOrder = BagsOrders::findOne(['id_bags' => $id_bag, 'statys_id' => 1]);
        $orderOne = Orders::findOne(['id_orders' => $bagOrder->id_orders]);
        $tastesOrderAll = OrdersTastes::findAll(['orders_id' => $bagOrder->id_orders]);
        $customerOne = Customers::findOne(['id_customers' => $orderOne->customers_id]);
        $testesArray = array();
        foreach ($tastesOrderAll as $itemTaste) {
            $testesArray[] = array(
                'tastes_id' => $itemTaste->tastes_id,
                //'orders_id' => 'Orders ID',
                'count' => $itemTaste->count,
            );
        }
        
        $resp = array(
            'id_bags' => $bagOne->id_bags,
            'tastes' => $testesArray
        );
        return $resp;
    }
    
    private function makeId() {
        $text = "dfg";
        $possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        
        for ($i = 0; $i < 5; $i++) {
            ///$text += $possible.charAt(Math.floor(Math.random() * $possible  .length));
            echo strCharAt($possible, floor(rand ( 0 , 1 ) * strlen($possible)));
            
            //$text .= char_at($possible, floor(rand ( 0 , 1 ) * strlen($possible)));
        }
        return $text;
    }

//var a = [];
//
//for (var i = 0; i<100;i++)
//{
//  a[i] = "as" + makeid();
//}
//
//console.log(a);

}
