<?php

namespace frontend\controllers;

use frontend\models\Users;
use frontend\models\Messages;
use frontend\models\Tasks;
use Taskforce\BusinessLogic\Task;
use frontend\models\Respond;

class EventsController extends SecuredController
{
    public function actionIndex()
    {
        $user = Users::find()->where(['id' => \Yii::$app->user->getId()])->one();

        $messages = '';

        if ($user->role === 'Заказчик') {
            $messages = Messages::find()
                ->where(['read' => '0', 'user_id_create' => null])
                ->all();

            foreach ($messages as $message) {
                $message['read'] = '1';
                $message->save();
            }
        }

        if ($user->role === 'Исполнитель') {
            $messages = Messages::find()
                ->where(['read' => '0', 'user_id_executor' => null])
                ->all();

            foreach ($messages as $message) {
                $message['read'] = '1';
                $message->save();
            }
        }

        $tasks_work = Tasks::find()
            ->where([
                'read_selected_executor' => '0',
                'status' => Task::STATUS_WORK,
                'user_id_executor' => \Yii::$app->user->getId()
            ])
            ->all();

        foreach ($tasks_work as $task) {
            $task['read_selected_executor'] = '1';
            $task->save();
        }

        $tasks_completed = Tasks::find()
            ->where([
                'read_task_completed' => '0',
                'status' => Task::STATUS_PERFORMED,
                'user_id_executor' => \Yii::$app->user->getId()
            ])
            ->all();

        foreach ($tasks_completed as $task) {
            $task['read_task_completed'] = '1';
            $task->save();
        }

        $tasks_failed = Tasks::find()
            ->where([
                'read_task_completed' => '0',
                'status' => Task::STATUS_FAILED,
                'user_id_create' => \Yii::$app->user->getId()
            ])
            ->all();

        foreach ($tasks_failed as $task) {
            $task['read_task_completed'] = '1';
            $task->save();
        }

        $user_tasks = Tasks::find()
            ->where(['user_id_create' => \Yii::$app->user->getId()])
            ->select('id')
            ->all();

        $responds = [];

        foreach ($user_tasks as $user_task) {
            $responds[] = Respond::find()
                ->where(['task_id' => $user_task['id'], 'read' => '0'])
                ->all();
        }

        foreach ($responds as $respond) {
            foreach ($respond as $value) {
                $value['read'] = '1';
                $value->save();
            }
        }
    }
}
