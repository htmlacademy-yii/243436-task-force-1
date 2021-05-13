<?php

namespace frontend\controllers;

use frontend\models\Users;
use frontend\models\UsersForm;
use yii\web\Controller;

class UsersController extends Controller
{
    public function actionIndex()
    {
        $this->view->title = 'Исполнители';

        $usersForm = new UsersForm();

        if(\Yii::$app->request->getIsGet()) {
            $usersForm->load(\Yii::$app->request->get());
        }

        $users = Users::find()
            ->where(['role' => 'Исполнитель'])
            ->joinWith(['tasksCreator', 'categories'])
            ->orderBy('date_visit DESC');

        if ($usersForm->category) {
            $users->andWhere(['users_and_categories.category_id' => $usersForm->category]);
        }

        if ((isset($usersForm->more[0]) && $usersForm->more[0] == 0)
        || (isset($usersForm->more[1]) && $usersForm->more[1] == 0
        || (isset($usersForm->more[2]) && $usersForm->more[2] == 0)
        || (isset($usersForm->more[3]) && $usersForm->more[3] == 0)
        )) {
            // $users->andWhere('tasks.user_id_executor IS NULL');
            $users->andWhere(['tasks.user_id_executor' => 21]);
        }

        $users = $users->all();

        // debug($_GET);
        // debug($users);

        return $this->render('index', compact('users', 'usersForm'));
    }
}
