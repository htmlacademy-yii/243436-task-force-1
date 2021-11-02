<?php

namespace frontend\controllers;

use yii\web\Controller;
use app\components\AuthHandler;

class AuthController extends Controller
{
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'], //После того как VKontakte отвечает, мы передаем управление на метод onAuthSuccess($client)
            ],
        ];
    }

    public function onAuthSuccess($client)
    {
        (new AuthHandler($client))->handle();
    }
}

