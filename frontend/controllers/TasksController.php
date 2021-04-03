<?php

namespace frontend\controllers;

use frontend\models\Tasks;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $this->view->title = 'Новый задания';

        $tasks = Tasks::find()
            ->where(['status' => 'Новое'])
            ->joinWith(['category', 'city', 'creator', 'executor'])
            ->orderBy('date_add DESC')->all();

        return $this->render('index', compact('tasks'));
    }
}
