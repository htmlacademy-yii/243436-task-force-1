<?php

namespace frontend\controllers;

use yii\web\Controller;
use frontend\models\AuthForm;
use yii\web\Response;
use yii\widgets\ActiveForm;
use frontend\models\Tasks;

class LandingController extends Controller
{
    public $layout = 'landing';

    public function actionIndex()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect('/tasks/index');
        }

        $this->view->title = 'TaskForce';

        $authForm = new AuthForm();

        \Yii::$app->getView()->params['authForm'] = $authForm;

        if (\Yii::$app->request->getIsPost()) {
            $authForm->load(\Yii::$app->request->post());
            if (\Yii::$app->request->isAjax) {
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($authForm);
            }
            if ($authForm->validate()) {
                $user = $authForm->getUser();
                \Yii::$app->user->login($user);
                return $this->goHome();
            }
        }

        $tasks = Tasks::find()
            ->where(['status' => 'Новое'])
            ->joinWith(['category', 'city', 'creator', 'executor'])
            ->limit(4)
            ->orderBy('date_add DESC')
            ->all();

        return $this->render('index', compact('tasks'));
    }
}

