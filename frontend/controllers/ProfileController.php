<?php
namespace frontend\controllers;
use frontend\models\Users;
use yii\web\Controller;

class ProfileController extends Controller
{
    public function actionUser()
    {
        $id = \Yii::$app->user->getId();

        if ($id) {
            return Users::findOne($id);
        }
    }

    public function actionLogout() {
        \Yii::$app->user->logout();

        return $this->goHome();
    }
}
