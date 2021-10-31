<?php

namespace frontend\controllers;

use frontend\models\Cities;
use yii\web\Controller;
use frontend\models\Users;
use Taskforce\BusinessLogic\Task;

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
                $user_form->role = Task::CREATOR;
                $user_form->new_message = '1';
                $user_form->action_task = '1';
                $user_form->new_review = '1';
                $user_form->path = 'img/avtar.png';

                $user_form->save();
                $user = $user_form->getUser();
                \Yii::$app->user->login($user);
                $this->goHome();
            }
        }

        return $this->render('index', compact('user_form', 'cities_list'));
    }
}

