<?php
namespace frontend\controllers;

use yii\web\Controller;
use app\components\AuthHandler;

class AuthController extends Controller
{
    /**
     * Вызов метода авторизации, после ответа клиента
     */
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    /**
     * Авторизация пользователя
     */
    public function onAuthSuccess($client)
    {
        (new AuthHandler($client))->handle();
    }
}
