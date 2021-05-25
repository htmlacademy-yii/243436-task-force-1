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

        $tasksForm->load(\Yii::$app->request->get());

        $tasks = Tasks::find()
            ->where(['status' => 'Новое'])
            ->joinWith(['category', 'city', 'creator', 'executor'])
            ->orderBy('date_add DESC');

        if ($tasksForm->category) {
            $tasks->andWhere(['category_id' => $tasksForm->category]);
        }

        if(!(isset($tasksForm->more[0]) && isset($tasksForm->more[1]))) {
            if (!empty($tasksForm->more)) {
                if (in_array($tasksForm::NOT_EXECUTOR, $tasksForm->more)) {
                    $tasks->andWhere('user_id_executor IS NULL');
                }
                if (in_array($tasksForm::DISTANT_WORK, $tasksForm->more)) {
                    $tasks->andWhere('tasks.city_id IS NULL');
                }
            }
        }

        if (isset($tasksForm->period) && $tasksForm->period === 'day') {
            $tasks->andWhere('tasks.date_add BETWEEN CURDATE() AND (CURDATE() + 1)');
        }

        if (isset($tasksForm->period) && $tasksForm->period === 'week') {
            $tasks->andWhere('tasks.date_add >= DATE_SUB(NOW(), INTERVAL 7 DAY)');
        }

        if (isset($tasksForm->period) && $tasksForm->period === 'month') {
            $tasks->andWhere('tasks.date_add >= DATE_SUB(NOW(), INTERVAL 30 DAY)');
        }

        if ($tasksForm->search) {
            $tasks->andWhere(['like', 'tasks.name', $tasksForm->search]);
        }

        $tasks = $tasks->all();

        return $this->render('index', compact('tasks', 'tasksForm'));
    }
}
