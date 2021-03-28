<?php

namespace frontend\controllers;

use frontend\models\Users;
use yii\web\Controller;

class UsersController extends Controller
{
    public function actionIndex()
    {
        $users = Users::find()
            ->joinWith(['tasks', 'usersAndSkills'])
            ->orderBy('date_visit DESC')->all();

        $count = Users::find()
            ->count();

        return $this->render('index', compact('users', 'count'));
    }
}
