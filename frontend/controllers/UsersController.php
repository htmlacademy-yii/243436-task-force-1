<?php

namespace frontend\controllers;

use frontend\models\Users;
use yii\web\Controller;

class UsersController extends Controller
{
    public function actionIndex()
    {
        $this->view->title = 'Исполнители';

        $users = Users::find()
            ->where(['role' => 'Исполнитель'])
            ->joinWith(['tasksCreator', 'skills'])
            ->orderBy('date_visit DESC')->all();

        return $this->render('index', compact('users'));
    }
}
