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
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\data\Pagination;

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

        $pages = new Pagination([
            'totalCount' => $tasks->count(),
            'pageSize' => 4,
            'forcePageParam' => false,
            'pageSizeParam' => false,
        ]);

        $tasks = $tasks->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index', compact('tasks', 'tasksForm', 'categories', 'pages'));
    }

    public function actionView($id)
    {
        $tasks = Tasks::find()
            ->where(['tasks.id' => $id])
            ->one();

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

                    try {
                        \Yii::$app->mailer->compose('failed', ['task' => $tasks->name, 'task_id' => $tasks->id])
                            ->setFrom([\Yii::$app->params['senderEmail'] => \Yii::$app->params['senderName']])
                            ->setTo([$tasks->creator->email, \Yii::$app->params['adminEmail']])
                            ->setSubject('Отказ от задания')
                            ->send();
                    } catch (\Swift_TransportException $e) {
                        debug($e);
                        die();
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

                    try {
                        \Yii::$app->mailer->compose('win', ['task' => $tasks->name, 'create' => $tasks->creator->name,
                        'task_id' => $tasks->id])
                            ->setFrom([\Yii::$app->params['senderEmail'] => \Yii::$app->params['senderName']])
                            ->setTo([$selectUser->executor->email, \Yii::$app->params['adminEmail']])
                            ->setSubject('Подтверждение отклика')
                            ->send();
                    } catch (\Swift_TransportException $e) {
                        debug($e);
                        die();
                    }

                    $tasks->user_id_executor = $selectUser->user_id_executor;
                    $tasks->status = task::STATUS_WORK;
                    if($tasks->save()) {
                        $this->redirect(['tasks/view', 'id' => \Yii::$app->request->get('id')]);
                    }
                }
            }
        }

        $respond = new Respond();

        $respondForMail = '';

        if (\Yii::$app->request->getIsPost()) {
            $respond->load(\Yii::$app->request->post());
            if (\Yii::$app->request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($respond);
            }
            if ($respond->validate()) {
                $respond->save(false);

                try {
                    \Yii::$app->mailer->compose('respond', ['task' => $tasks->name, 'task_id' => $tasks->id])
                        ->setFrom([\Yii::$app->params['senderEmail'] => \Yii::$app->params['senderName']])
                        ->setTo([$tasks->creator->email, \Yii::$app->params['adminEmail']])
                        ->setSubject('Новый отклик по задаче')
                        ->send();
                } catch (\Swift_TransportException $e) {
                    debug($e);
                    die();
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

        if ($user->role === 'Исполнитель') {
            $executorID = $user->id;
        }

        if ($user->role === 'Заказчик') {
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

                    $respondForMail = Respond::find()
                        ->where(['task_id' => $id, 'status' => 'Подтверждено'])
                        ->joinWith(['executor'])
                        ->all();

                    try {
                        \Yii::$app->mailer->compose('end', ['task' => $tasks->name, 'create' => $tasks->creator->name,
                        'task_id' => $tasks->id])
                            ->setFrom([\Yii::$app->params['senderEmail'] => \Yii::$app->params['senderName']])
                            ->setTo([$respondForMail[0]->executor->email, \Yii::$app->params['adminEmail']])
                            ->setSubject('Завершение задачи')
                            ->send();
                    } catch (\Swift_TransportException $e) {
                        debug($e);
                        die();
                    }

                    if($tasks->save()) {
                        $this->redirect(['tasks/index']);
                    }
                }
            }
        }

        $messagesForm = new Messages();

        $send_email = '';

        $messages = '';

        if (\Yii::$app->request->getIsPost()) {
            $messagesForm->load(\Yii::$app->request->post());

            if ($messagesForm->validate()) {
                if($messagesForm->save()) {

                    $messages = Messages::find()
                        ->where(['task_id' => $id])
                        ->orderBy('date_add DESC')
                        ->limit(1)
                        ->all();


                    if (!$messages[0]->user_id_create) {
                        $send_email = $tasks->creator->email;
                    } else {
                        $send_email = $tasks->executor->email;
                    }

                    try {
                        \Yii::$app->mailer->compose('message', ['task' => $tasks->name, 'task_id' => $tasks->id])
                            ->setFrom([\Yii::$app->params['senderEmail'] => \Yii::$app->params['senderName']])
                            ->setTo([$send_email, \Yii::$app->params['adminEmail']])
                            ->setSubject('Новое сообщение по задаче')
                            ->send();
                    } catch (\Swift_TransportException $e) {
                        debug($e);
                        die();
                    }

                    $this->redirect(['tasks/view', 'id' => \Yii::$app->request->get('id')]);
                }
            }
        }

        $messages = Messages::find()
            ->where(['task_id' => $id])
            ->all();

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
                'messagesForm',
                'messages'
            )
        );
    }
}
