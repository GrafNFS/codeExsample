<?php
use app\xj\qrcode\QRcode;
use app\xj\qrcode\widgets\Text;
//use app\xj\qrcode\widgets\Email;
use app\xj\qrcode\widgets\Card;
$qr = "asMMlOo,asU3eSa,asyre9N,asTjmQK,asfDsiA,asov11A,asZA9LR,asT76P3,askzrxL,asFY3gY,asPH8Qe,asvS4ql,asoLoB6,asJo2Yp,asNUBLV,asmOgNu,asDIadY,aszqHrd,asy7qny,asjDH7R,asjiaQN,asnVkQK,asHefi8,asusQGJ,asIVNX8,as29z7l,asGno1h,askmp9D,as7wRCY,asT5jtm,asl7quw,ash00V2,ash4wfv,as5Xewl,asl0rrB,as981hl,asw8Sw7,asDhKZu,asaCYJC,aspR9hC,asqdqCi,asr1NPi,asmjgdO,asipcTD,asL1rNO,asYSVmz,as2Sibj,aszF6OM,asMrh4V,as4jiHW,asqktgU,asUT5LA,asr9WlS,asSodJ1,aszgy3p,as5oHy3,asP0jin,asxd9rB,aspWtgI,asPbl86,as7kdiW,asJhenj,as57UtI,as26FG4,as96h4g,asyZg3i,asfZRcU,astL5IQ,aswmNkf,asUMc3w,asp8ihQ,asq1bas,asFpkMH,asUBiUL,asqZAcS,asY2NEF,as6XojB,asIPY9Y,as1omfz,asNYBpp,asmO3wc,asfJl3o,asgSpFj,asqmxFJ,asuNqHL,asDIkwE,as47uLy,aseSUGA,as67wHE,asrzARP,asgFPXm,as9rI4I,asmSWBA,aslRAab,assFh28,as5ROWs,as4PIfY,asWBuAu,asgjXXL,ash5tFi";
//Widget create a QR Image Url //QR Created by Widget
echo Text::widget([
    //'outputDir' => '@webroot/upload/qrcode',
    'outputDirWeb' => '@web/upload/qrcode',
    'ecLevel' => QRcode::QR_ECLEVEL_L,
    'text' => "test",
    'size' => 6,
]);

//Widget create a Action URL //QR Create by Action
//echo Text::widget([
//    //'actions' => ['qrcode/index'],
//    'text' => 'aaaa@gmail.com',
//    'size' => 3,
//    'margin' => 4,
//    'ecLevel' => QRcode::QR_ECLEVEL_L,
//]);

//other type
//Create EMAIL
//Email::widget([
//    'email' => 'aaaa@gmail.com',
//    'subject' => 'myMail',
//    'body' => 'do something',
//]);

//Create Card
//Card::widget([
//    'actions' => ['clientQrcode'],
//    'name' => 'SB',
//    'phone' => '1111111111111',
//    //here jpeg file is only 40x40, grayscale, 50% quality! 
//    'avatar' => '@webroot/avatar.jpg',
//]);

//Create Sms
//Smsphone::widget([
//    'actions' => ['clientQrcode'],
//    'phone' => '131111111111',
//]);
//Create Tel
//Telphone::widget([
//    'actions' => ['clientQrcode'],
//    'phone' => '131111111111',
//]);
