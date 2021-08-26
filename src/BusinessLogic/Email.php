<?php

namespace Taskforce\BusinessLogic;
use frontend\models\Respond;
use frontend\models\Messages;

class Email
{
    /**
     * Отправка email заказчику об отказе исполнителя от задания
     */
    public function failedAction()
    {
        try {
            \Yii::$app->mailer->compose('failed', ['task' => \Yii::$app->params['task_current']->name,
            'task_id' => \Yii::$app->params['task_current']->id])
                ->setFrom([\Yii::$app->params['senderEmail'] => \Yii::$app->params['senderName']])
                ->setTo([\Yii::$app->params['task_current']->creator->email, \Yii::$app->params['adminEmail']])
                ->setSubject('Отказ от задания')
                ->send();
        } catch (\Swift_TransportException $e) {
            debug($e);
            die();
        }
    }

    /**
     * Отправка email исполнителю о подтверждении отклика по заданию
     */
    public function winAction()
    {
        $selectUser = Respond::find()
            ->where(['task_id' => \Yii::$app->request->get('id'), 'status' => 'Подтверждено'])
            ->joinWith(['executor'])
            ->one();

        try {
            \Yii::$app->mailer->compose('win', ['task' => \Yii::$app->params['task_current']->name,
            'create' => \Yii::$app->params['task_current']->creator->name,
            'task_id' => \Yii::$app->params['task_current']->id])
                ->setFrom([\Yii::$app->params['senderEmail'] => \Yii::$app->params['senderName']])
                ->setTo([$selectUser->executor->email, \Yii::$app->params['adminEmail']])
                ->setSubject('Подтверждение отклика')
                ->send();
        } catch (\Swift_TransportException $e) {
            debug($e);
            die();
        }
    }

    /**
     * Отправка email заказчику о новом отклике по заданию
     */
    public function respondAction()
    {
        try {
            \Yii::$app->mailer->compose('respond', ['task' => \Yii::$app->params['task_current']->name,
            'task_id' => \Yii::$app->params['task_current']->id])
                ->setFrom([\Yii::$app->params['senderEmail'] => \Yii::$app->params['senderName']])
                ->setTo([\Yii::$app->params['task_current']->creator->email, \Yii::$app->params['adminEmail']])
                ->setSubject('Новый отклик по задаче')
                ->send();
        } catch (\Swift_TransportException $e) {
            debug($e);
            die();
        }
    }

    /**
     * Отправка email исполнителю о завершении задания
     */
    public function endAction()
    {
        $respondForMail = Respond::find()
            ->where(['task_id' => \Yii::$app->request->get('id'), 'status' => 'Подтверждено'])
            ->joinWith(['executor'])
            ->all();

        try {
            \Yii::$app->mailer->compose('end', ['task' => \Yii::$app->params['task_current']->name,
            'create' => \Yii::$app->params['task_current']->creator->name,
            'task_id' => \Yii::$app->params['task_current']->id])
                ->setFrom([\Yii::$app->params['senderEmail'] => \Yii::$app->params['senderName']])
                ->setTo([$respondForMail[0]->executor->email, \Yii::$app->params['adminEmail']])
                ->setSubject('Завершение задачи')
                ->send();
        } catch (\Swift_TransportException $e) {
            debug($e);
            die();
        }
    }

    /**
     * Отправка email исполнителю/заказчику о новом сообщении по задаче
     */
    public function messageAction()
    {
        $messages = Messages::find()
            ->where(['task_id' => \Yii::$app->request->get('id')])
            ->orderBy('date_add DESC')
            ->limit(1)
            ->all();

        if (!$messages[0]['user_id_executor']) {
            $send_email = $messages[0]->task->executor->email;
        } else {
            $send_email = $messages[0]->task->creator->email;
        }

        try {
            \Yii::$app->mailer->compose('message', ['task' => \Yii::$app->params['task_current']->name,
            'task_id' => \Yii::$app->params['task_current']->id])
                ->setFrom([\Yii::$app->params['senderEmail'] => \Yii::$app->params['senderName']])
                ->setTo([$send_email, \Yii::$app->params['adminEmail']])
                ->setSubject('Новое сообщение по задаче')
                ->send();
        } catch (\Swift_TransportException $e) {
            debug($e);
            die();
        }
    }
}
