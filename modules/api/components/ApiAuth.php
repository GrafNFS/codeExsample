<?php

namespace app\modules\api\components;

use app\models\User;

class ApiAuth
{
    public function authenticate()
    {
        $token = $_SERVER['HTTP_TOKEN'];

        if ($token) {
            $identity = User::findIdentityByAccessToken($token);
            if ($identity != null) {
                return $identity;
            }
        }

        return null;
    }
}
