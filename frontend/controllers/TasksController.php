<?php
namespace frontend\controllers;

use frontend\models\Categories;
use frontend\models\Tasks;
use frontend\models\Clips;
use frontend\models\Users;
use frontend\models\Respond;
use frontend\models\Reviews;
use frontend\models\TasksForm;
use yii\web\NotFoundHttpException;
use Taskforce\BusinessLogic\Task;
use Taskforce\BusinessLogic\Email;
use yii\web\Response;
use yii\widgets\ActiveForm;

class TasksController extends SecuredController
{
    /**
     * Рендерит страницу index
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $this->view->title = 'Новый задания';

        $tasksForm = new TasksForm();

        $categories = new Categories();

        $tasksForm->load(\Yii::$app->request->get());

        $dataProvider = $tasksForm->filter();

        return $this->render('index', compact('tasksForm', 'categories', 'dataProvider'));
    }

    /**
     * Рендерит страницу view
     *
     * @return mixed
     */
    public function actionView($id)
    {
        $email = new Email();

        $tasks = Tasks::find()
            ->where(['tasks.id' => $id])
            ->one();

        \Yii::$app->params['task_current'] = $tasks;

        if (empty($tasks)) {
            throw new NotFoundHttpException('Страница не найдена...');
        }

        $this->view->title = $tasks['name'];

        if (\Yii::$app->request->getIsPost() && \Yii::$app->request->post('Tasks')) {
            if (\Yii::$app->request->post('Tasks')['status'] === Task::STATUS_CANCEL) {
                $tasks->status = Task::STATUS_CANCEL;
                if ($tasks->save()) {
                    $this->redirect(['tasks/view', 'id' => \Yii::$app->request->get('id')]);
                }
            }
            if (\Yii::$app->request->post('Tasks')['status'] === Task::STATUS_FAILED) {
                $tasks->status = Task::STATUS_FAILED;
                $tasks->read_task_completed = '0';
                if ($tasks->save()) {
                    $user = Users::find()
                        ->where(['users.id' => \Yii::$app->user->getId()])
                        ->one();

                    if ((int) $user->action_task === 1) {
                        $email->failedAction();
                    }

                    $this->redirect(['tasks/index']);
                }
            }
        }


        $tasksCount = Tasks::find()
            ->where(['user_id_create' => $tasks['user_id_create']])
            ->count();


        $now_time = time();
        $past_time = strtotime($tasks->creator->date_add);
        $result_time = floor(($now_time - $past_time) / 86400);


        $clips = Clips::find()
            ->where(['task_id' => $id])
            ->all();

        $responds = "";

        $allRespond = Respond::find()
            ->where(['task_id' => $id])
            ->joinWith(['executor'])
            ->all();

        if ((!empty($allRespond)
        && \Yii::$app->user->getId() === $tasks->user_id_create)) {
            $responds = $allRespond;
        }

        $oneRespond = Respond::find()
            ->where(['task_id' => $id, 'user_id_executor' => \Yii::$app->user->getId()])
            ->joinWith(['executor'])
            ->all();

        if (!empty($oneRespond)
        && \Yii::$app->user->getId() === $oneRespond[0]->user_id_executor) {
            $responds = $oneRespond;
        }

        $user = Users::find()
            ->where(['users.id' => \Yii::$app->user->getId()])
            ->one();

        foreach ($allRespond as $respond) {
            if (\Yii::$app->request->get($respond->user_id_executor) === 'false') {
                $respond->status = 'Отклонено';
                if ($respond->save()) {
                    $this->redirect(['tasks/view', 'id' => \Yii::$app->request->get('id')]);
                }
            }
            if (\Yii::$app->request->get($respond->user_id_executor) === 'true') {
                $respond->status = 'Подтверждено';
                if ($respond->save()) {
                    $selectUser = Respond::find()
                        ->where(['task_id' => $id, 'status' => 'Подтверждено'])
                        ->joinWith(['executor'])
                        ->one();

                    $tasks->user_id_executor = $selectUser->user_id_executor;
                    $tasks->status = task::STATUS_WORK;
                    $tasks->read_selected_executor = '0';

                    if ($tasks->save()) {
                        if ((int) $user->action_task === 1) {
                            $email->winAction();
                        }

                        $this->redirect(['tasks/view', 'id' => \Yii::$app->request->get('id')]);
                    }
                }
            }
        }

        $respond = new Respond();

        if (\Yii::$app->request->getIsPost()) {
            $respond->load(\Yii::$app->request->post());
            if (\Yii::$app->request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($respond);
            }
            if ($respond->validate()) {
                $respond->read = '0';
                $respond->save(false);

                if ((int) $user->action_task === 1) {
                    $email->respondAction();
                }

                $this->redirect(['tasks/view', 'id' => \Yii::$app->request->get('id')]);
            }
        }

        $task = new Task();

        $currentID = \Yii::$app->user->getId();
        $executorID = null;
        $creatorID = null;

        $user = Users::find()
            ->where(['id' => $currentID])
            ->one();

        if ($user->role === Task::EXECUTOR) {
            $executorID = $user->id;
        }

        if ($user->role === Task::CREATOR) {
            $creatorID = $user->id;
        }

        $review = new Reviews();

        if (\Yii::$app->request->getIsPost()) {
            $review->load(\Yii::$app->request->post());
            if (\Yii::$app->request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($review);
            }
            if ($review->validate()) {
                if ($review->save(false)) {
                    if ($review->status === 'Да') {
                        $tasks->status = task::STATUS_PERFORMED;
                    }
                    if ($review->status === 'Возникли проблемы') {
                        $tasks->status = task::STATUS_FAILED;
                    }

                    if ((int) $user->new_review === 1) {
                        $email->endAction();
                    }

                    $tasks->read_task_completed = '0';

                    if ($tasks->save()) {
                        $this->redirect(['tasks/index']);
                    }
                }
            }
        }

        return $this->render(
            'view',
            compact(
                'id',
                'tasks',
                'clips',
                'responds',
                'tasksCount',
                'result_time',
                'task',
                'currentID',
                'executorID',
                'creatorID',
                'oneRespond',
                'user',
            )
        );
    }
}
