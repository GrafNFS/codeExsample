<?php

class ApiBase {

    const ONLINE_TIME_LIMIT = 3;

    protected static $_resp = array(
        'status' => 0,
        'error' => null,
        'result' => null
    );

    /*public function sendMessage() {
        $push = new ParsePush(0);

        $args = array(
            'where' => array(
                'channels' => 'user' . $id,
            ),
            'data' => array(
                'alert' => $message,
                'action' => 'kolibri.mobi.marketa.UPDATE_STATUS'
            )
        );
        $push->pusher($args);
    }*/

    protected static $_user_id = null;
    protected static $_type_id = null;

    protected static function _check_auth() {
        if (!(isset($_SERVER['HTTP_APP_KEY']))) {
            static::_send_resp(null, 99, 'app key is not set');
        }
        $app_key = $_SERVER['HTTP_APP_KEY'];

        $user = Users::model()->findByAttributes(array('app_key' => $app_key));
        if ($user === null) {
            static::_send_resp(null, 99, 'email is invalid');
        } else if ($user->password !== $password) {
            static::_send_resp(null, 99, 'password is invalid');
        } else {
            static::$_user_id = $user->getPrimaryKey();
            static::$_type_id = $user->id_type;
            $user->last_active = new CDbExpression('NOW()');
            $user->save();
        }
    }

    protected static function _send_resp($result = null, $status = 0, $error = null) {
        if ($result)
            static::$_resp['result'] = $result;
        if ($status)
            static::$_resp['status'] = $status;
        if ($error)
            static::$_resp['error'] = $error;

        header('Content-type: application/json');
        echo CJSON::encode(static::$_resp);
        Yii::app()->end();
    }

    public static function unknown($error = null) {
        static::$_resp['status'] = 100;
        static::$_resp['error'] = $error? : 'unknown method';
        static::_send_resp();
    }

    protected static function _paging($total, $limit = 10, $before = null, $after = null) {
        if (!$before && !$after) {
            $prev = null;
            $next = $limit <= $total ? $limit : null;
        } else if ($after) {
            $prev = $after - $limit >= 0 ? $after - $limit : null;
            $next = $after + $limit <= $total ? $after + $limit : $total;
        } else if ($before) {
            $prev = $before - $limit >= 0 ? $before - $limit : null;
            $next = $before + $limit <= $total ? $before + $limit : null;
        }
        return array($prev, $next);
    }

}
