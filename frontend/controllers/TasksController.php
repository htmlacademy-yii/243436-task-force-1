<?php

namespace frontend\controllers;

use frontend\models\Categories;
use frontend\models\Tasks;
use frontend\models\Clips;
use frontend\models\Messages;
use frontend\models\Users;
use frontend\models\Respond;
use frontend\models\Reviews;
use frontend\models\TasksForm;
use yii\web\NotFoundHttpException;
use Taskforce\BusinessLogic\Task;
use Taskforce\BusinessLogic\Email;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;

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

        $dataProvider = new ActiveDataProvider([
            'query' => $tasks,
            'pagination' => [
                'pageSize' => 5,
                'pageSizeParam' => false
            ]
        ]);

        return $this->render('index', compact('tasksForm', 'categories', 'dataProvider'));
    }

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
            if (\Yii::$app->request->post('Tasks')['status'] === task::STATUS_CANCEL) {
                $tasks->status = task::STATUS_CANCEL;
                if ($tasks->save()) {
                    $this->redirect(['tasks/view', 'id' => \Yii::$app->request->get('id')]);
                }
            }
            if (\Yii::$app->request->post('Tasks')['status'] === task::STATUS_FAILED) {
                $tasks->status = task::STATUS_FAILED;
                if ($tasks->save()) {

                    $email->failedAction();

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

        $selectUser = '';

        foreach ($allRespond as $respond) {
            if (\Yii::$app->request->get($respond->user_id_executor) === 'false') {
                $respond->status = 'Отклонено';
                if($respond->save()) {
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

                    $email->winAction();

                    $tasks->user_id_executor = $selectUser->user_id_executor;
                    $tasks->status = task::STATUS_WORK;
                    if($tasks->save()) {
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
                $respond->save(false);

                $email->respondAction();

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

                    $email->endAction();

                    if($tasks->save()) {
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
