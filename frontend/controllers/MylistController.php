<?php

namespace frontend\controllers;

use frontend\models\Tasks;
use yii\data\ActiveDataProvider;
use Taskforce\BusinessLogic\Task;

class MylistController extends SecuredController
{
    public function actionIndex($status)
    {
        $this->view->title = 'Мои задания';

        $filter = '';

        if ($status === 'performed') {
            $filter = Task::STATUS_PERFORMED;
        } elseif ($status === 'new') {
            $filter = Task::STATUS_NEW;
        } elseif ($status === 'work') {
            $filter = Task::STATUS_WORK;
        } elseif ($status === 'cancel' || $status === 'Провалено') {
            $filter = [Task::STATUS_CANCEL, Task::STATUS_FAILED];
        } elseif ($status === 'failed') {
            $filter = Task::STATUS_WORK;
        }

        $tasks = Tasks::find()
            ->where(['status' => $filter])
            ->joinWith(['category', 'city', 'creator', 'executor'])
            ->orderBy('date_add DESC');

        if ($status === 'failed') {
            $tasks ->andWhere("expire > DATE_ADD(NOW(), INTERVAL 0 DAY)");
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $tasks,
            'pagination' => [
                'pageSize' => 5,
                'pageSizeParam' => false
            ]
        ]);

        return $this->render('index', compact('dataProvider', 'tasks', 'status'));
    }
}
