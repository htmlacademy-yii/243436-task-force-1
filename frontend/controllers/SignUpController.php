<?php

namespace frontend\controllers;

use frontend\models\Cities;
use yii\web\Controller;
use frontend\models\Users;

class SignupController extends Controller
{
    public function actionIndex()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->view->title = 'Регистрация аккаунта';

        $user_form = new Users;

        $cities = new Cities();

        $cities_list = $cities->citiesList();

        if (\Yii::$app->request->getIsPost()) {
            $user_form->load(\Yii::$app->request->post());

            if ($user_form->validate()) {
                $user_form->password = \Yii::$app->security->generatePasswordHash($user_form->password);
                $user_form->save(false);
                $user = $user_form->getUser();
                \Yii::$app->user->login($user);
                $this->goHome();
            }
        }

        return $this->render('index', compact('user_form', 'cities_list'));
    }
}

