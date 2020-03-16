<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_message".
 *
 * @property int $id_message
 * @property int $msg_from
 * @property int $msg_to
 * @property string $text_msg
 * @property int $status
 * @property string $date_create
 */
class Message extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'tbl_message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['msg_from', 'msg_to', 'status'], 'integer'],
            [['text_msg'], 'string'],
        ];
    }

    public static function saveMessage($from, $to, $msg) {
        $newMessage = new Message();
        $newMessage->msg_from = $from;
        $newMessage->msg_to = $to;
        $newMessage->text_msg = $msg;
        $newMessage->date_create = date("Y-m-d H:i:s");
        if ($newMessage->save()) {
            return $newMessage;
        } else {
            return $newMessage->errors;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id_message' => 'Id Message',
            'msg_from' => 'Msg From',
            'msg_to' => 'Msg To',
            'text_msg' => 'Text Msg',
            'status' => 'Status',
        ];
    }

}
