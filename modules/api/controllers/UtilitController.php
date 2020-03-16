<?php
namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;

class UtilitController  extends Controller {
    
    public function base64_to_file($base64_string, $output_file) {
        $path = Yii::getAlias("/var/www/avareapp/web/upload/");
        // open the output file for writing
        $ifp = fopen($path.$output_file, 'wb'); 

        $data = explode(',', $base64_string);
        if(count($data)>1) {
            $dataText=$data[1];
        } else {
            $dataText=$base64_string;
        }

        fwrite($ifp, base64_decode($dataText));

        fclose($ifp); 

        return $output_file; 
    }
    
    public function file_to_base64($file) {
        $path = Yii::getAlias("/var/www/avareapp/web/upload/");
        if (file_exists($path . $file) && !empty($file)) {
            $imagedata = file_get_contents($path . $file);
            $base64 = base64_encode($imagedata);

            return /*"http://appserv.247avare.com:9996/web/upload/" . $file;*/$base64; 
        }
        else {
            return null;
        }
    }
}

