<?php

namespace frontend\modules\v1\controllers;

use frontend\models\Messages;
use frontend\models\Tasks;
use frontend\models\Users;
use yii\rest\ActiveController;
use Taskforce\BusinessLogic\Email;


class MessageController extends ActiveController
{
    public $modelClass = Messages::class;

    public function behaviors() {
        return [
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'formatParam' =>'_format',
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                    'xml' =>\yii\web\Response::FORMAT_XML,
                ],
            ],
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['index']);
        return $actions;
    }

    public function actionCreate()
    {
        $email = new Email();

        $model = new $this->modelClass();

        $content = json_decode(\Yii::$app->getRequest()->getRawBody(), true);

        if ($content) {
            \Yii::$app->response->statusCode = 201;
        }

        $tasks = Tasks::find()
            ->where(['tasks.id' => $content['task_id']])
            ->one();

        $creatorID = null;
        $executorID = null;

        if (\Yii::$app->user->getId() === $tasks->user_id_create) {
            $creatorID = \Yii::$app->user->getId();
        } else {
            $executorID = \Yii::$app->user->getId();
        }

        if (\Yii::$app->request->getIsPost()) {
            $model->message = $content['message'];
            $model->task_id = $content['task_id'];
            $model->user_id_executor = $executorID;
            $model->user_id_create = $creatorID;
            $model->read = '0';
            $model->save();

            $user = Users::find()
                ->where(['users.id' => \Yii::$app->user->getId()])
                ->one();

            if ((int) $user->new_message === 1) {
                $email->messageAction($content['task_id']);
            }

            $message_count = Messages::find()->where(['task_id' => $content['task_id']])->count();

            $tasks->new_messages = $message_count;

            $tasks->save();

        }

        return $model;
    }

    public function actionIndex()
    {
        $messages = $this->modelClass::find()
            ->where(['task_id' => \Yii::$app->request->get('id')])
            ->all();


        if ($messages) {
            \Yii::$app->response->statusCode = 200;
        }

        return $messages;
    }
}
