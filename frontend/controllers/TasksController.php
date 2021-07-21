<?php

namespace frontend\controllers;

use frontend\models\Categories;
use frontend\models\Tasks;
use frontend\models\Clips;
use frontend\models\Respond;
use frontend\models\TasksForm;
use yii\web\NotFoundHttpException;

class TasksController extends SecuredController
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

    public function actionView($id)
    {
        $tasks = Tasks::find()
            ->where(['tasks.id' => $id])
            ->one();

        if (empty($tasks)) {
            throw new NotFoundHttpException('Страница не найдена...');
        }

        $tasksCount = Tasks::find()
            ->where(['user_id_create' => $tasks['user_id_create']])
            ->count();

        $clips = Clips::find()
            ->where(['task_id' => $id])
            ->all();

        $responds = Respond::find()
            ->where(['task_id' => $id])
            ->joinWith(['executor'])
            ->all();

        $this->view->title = $tasks['name'];

        $now_time = time();
        $past_time = strtotime($tasks->creator->date_add);
        $result_time = floor(($now_time - $past_time) / 86400);

        return $this->render(
            'view', compact('id', 'tasks', 'clips', 'responds', 'tasksCount', 'result_time')
        );
    }
}
