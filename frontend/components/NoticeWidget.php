<?php
namespace frontend\components;

use yii\base\Widget;
use frontend\models\Users;
use frontend\models\Messages;
use frontend\models\Respond;
use frontend\models\Tasks;
use Taskforce\BusinessLogic\Task;

class NoticeWidget extends Widget
{
    public function run()
    {
        $user = Users::find()->where(['id' => \Yii::$app->user->getId()])->one();

        $messages = '';

        if ($user->role === Task::CREATOR) {
            $messages = Messages::find()
                ->where(['read' => '0', 'user_id_create' => null])
                ->select('task_id')
                ->with('task')
                ->all();
        }

        if ($user->role === Task::EXECUTOR) {
            $messages = Messages::find()
                ->where(['read' => '0', 'user_id_executor' => null])
                ->select('task_id')
                ->with('task')
                ->all();
        }

        $tasks_work = Tasks::find()
            ->where([
                'read_selected_executor' => '0',
                'status' => Task::STATUS_WORK,
                'user_id_executor' => \Yii::$app->user->getId()
            ])
            ->all();

        $tasks_completed = Tasks::find()
            ->where([
                'read_task_completed' => '0',
                'status' => Task::STATUS_PERFORMED,
                'user_id_executor' => \Yii::$app->user->getId()
            ])
            ->all();

        $tasks_failed = Tasks::find()
            ->where([
                'read_task_completed' => '0',
                'status' => Task::STATUS_FAILED,
                'user_id_create' => \Yii::$app->user->getId()
            ])
            ->all();

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

        return $this->render(
            'notice',
            compact('user', 'messages', 'tasks_work', 'tasks_completed', 'tasks_failed', 'responds')
        );
    }
}
