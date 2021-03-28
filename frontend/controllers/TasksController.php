<?php

namespace frontend\controllers;

use frontend\models\Tasks;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $tasks = Tasks::find()
            ->joinWith(['category', 'city', 'userIdCreate', 'userIdExecutor'])
            ->orderBy('date_add DESC')->all();

        return $this->render('index', compact('tasks'));
    }
}
