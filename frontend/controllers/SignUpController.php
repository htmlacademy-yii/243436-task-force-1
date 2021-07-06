<?php

namespace frontend\controllers;

use frontend\models\Cities;
use yii\web\Controller;
use frontend\models\Users;


class SignupController extends Controller
{
    public function actionIndex()
    {
        $this->view->title = 'Регистрация аккаунта';

        $user_form = new Users;

        $cities = new Cities();

        if(\Yii::$app->request->getIsPost()) {
            $user_form->load(\Yii::$app->request->post());

            if($user_form->validate()) {
                $user_form->password = \Yii::$app->security->generatePasswordHash($user_form->password);
                $user_form->save(false);
                $this->goHome();
            }
        }

        return $this->render('index', compact('user_form', 'cities'));
    }
}

