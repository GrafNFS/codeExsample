<?php

class ApiC extends ApiBase {
    
    public static function send_message() {
        static::_check_auth();
        
        $newMessage = new Message();
        $data = array(
            "msg_from" => static::$_user_id,
            "msg_to" => Yii::app()->getRequest()->getPost('msg_to'),
            "text_msg" => Yii::app()->getRequest()->getPost('text_msg'),
            "status" => 1,
        );
        $newMessage->attributes = $data;
        if (!$newMessage->save()) {
            static::_send_resp(null, 101, $newMessage->getErrors());
        }
        else {
            static::_send_resp($newMessage);
        }
    }
   
    public static function view_all_message() {
        static::_check_auth();
        $userTo = Yii::app()->getRequest()->getPost('msg_to');
        
        $messageAll = Message::model()->findAll("msg_from=:msg_from and msg_to=:msg_to", array(":msg_from" => static::$_user_id, ":msg_to" => $userTo));
        if (isset($messageAll) && $messageAll != null) {
            static::_send_resp($messageAll);
        }
        else {
            static::_send_resp(null, 204, "No records");
        }
    }
}