<?php

namespace frontend\controllers;

use frontend\models\Categories;
use frontend\models\Tasks;
use frontend\models\TasksForm;
use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $this->view->title = 'Новый задания';

        $tasksForm = new TasksForm();

        $categories = new Categories();

        $tasksForm->load(\Yii::$app->request->get());

        $tasks = Tasks::find()
            ->where(['status' => 'Новое'])
            ->joinWith(['category', 'city', 'creator', 'executor'])
            ->orderBy('date_add DESC');

        if ($tasksForm->category) {
            $tasks->andWhere(['category_id' => $tasksForm->category]);
        }

        if (is_array($tasksForm->more)) {

            $conditions = [];

            if (in_array($tasksForm::NOT_EXECUTOR, $tasksForm->more)) {
                $conditions[] = 'user_id_executor IS NULL';
            }
            if (in_array($tasksForm::DISTANT_WORK, $tasksForm->more)) {
                $conditions[] = 'tasks.city_id IS NULL';
            }

            if (count($conditions) > 0) {
                $tasks->andWhere(implode(" or ", $conditions));
            }
        }

        if ($tasksForm->period === $tasksForm::DAY) {
            $tasks->andWhere('tasks.date_add BETWEEN CURDATE() AND (CURDATE() + 1)');
        }

        if ($tasksForm->period === $tasksForm::WEEK) {
            $tasks->andWhere('tasks.date_add >= DATE_SUB(NOW(), INTERVAL 7 DAY)');
        }

        if ($tasksForm->period === $tasksForm::MONTH) {
            $tasks->andWhere('tasks.date_add >= DATE_SUB(NOW(), INTERVAL 30 DAY)');
        }

        if ($tasksForm->search) {
            $tasks->andWhere(['like', 'tasks.name', $tasksForm->search]);
        }

        $tasks = $tasks->all();

        return $this->render('index', compact('tasks', 'tasksForm', 'categories'));
    }
}
