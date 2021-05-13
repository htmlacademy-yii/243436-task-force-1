<?php

namespace frontend\controllers;

use frontend\models\Tasks;
use frontend\models\TasksForm;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $this->view->title = 'Новый задания';

        $tasksForm = new TasksForm();

        if(\Yii::$app->request->getIsGet()) {
            $tasksForm->load(\Yii::$app->request->get());
        }

        $tasks = Tasks::find()
            ->where(['status' => 'Новое'])
            ->joinWith(['category', 'city', 'creator', 'executor'])
            ->orderBy('date_add DESC');

        if ($tasksForm->category) {
            $tasks->andWhere(['category_id' => $tasksForm->category]);
        }

        if(!(isset($tasksForm->more[0]) && isset($tasksForm->more[1]))) {
            if ((isset($tasksForm->more[0]) && $tasksForm->more[0] == 1)
            || (isset($tasksForm->more[1]) && $tasksForm->more[1] == 1)) {
                $tasks->andWhere('user_id_executor IS NULL');
            }

            if ((isset($tasksForm->more[0]) && $tasksForm->more[0] == 2)
            || (isset($tasksForm->more[1]) && $tasksForm->more[1] == 2)) {
                $tasks->andWhere('tasks.city_id IS NULL');
            }
        }

        if (\Yii::$app->request->get('time') === 'day') {
            $tasks->andWhere('tasks.date_add BETWEEN CURDATE() AND (CURDATE() + 1)');
        }

        if (\Yii::$app->request->get('time') === 'week') {
            $tasks->andWhere('tasks.date_add >= DATE_SUB(NOW(), INTERVAL 7 DAY)');
        }

        if (\Yii::$app->request->get('time') === 'month') {
            $tasks->andWhere('tasks.date_add >= DATE_SUB(NOW(), INTERVAL 30 DAY)');
        }

        if (\Yii::$app->request->get('q')) {
            $tasks->andWhere(['like', 'tasks.name', $_GET['q']]);
        }

        $tasks = $tasks->all();

        return $this->render('index', compact('tasks', 'tasksForm'));
    }
}
