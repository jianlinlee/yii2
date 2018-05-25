<?php

namespace common\check;

use Yii;

class CheckLogin
{
    public function isLogin()
    {
        $session = Yii::$app->session;
        if (!isset($session['user']) || empty($session['user'])) {
            // 进入登陆页
        }
    }
}