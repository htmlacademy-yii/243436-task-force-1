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

        $userIdExecutor = new Users();


        if(\Yii::$app->request->getIsGet()) {
            $usersForm->load(\Yii::$app->request->get());
        }

        $users = Users::find()
            ->where(['role' => 'Исполнитель'])
            ->joinWith(['tasksExecutor', 'categories', 'reviewsExecutor', 'favorites'])
            ->orderBy('date_visit DESC');

        if ($usersForm->category) {
            $users->andWhere(['users_and_categories.category_id' => $usersForm->category]);
        }

        if ((isset($usersForm->more[0]) && $usersForm->more[0] == 0)
        || (isset($usersForm->more[1]) && $usersForm->more[1] == 0)
        || (isset($usersForm->more[2]) && $usersForm->more[2] == 0)
        || (isset($usersForm->more[3]) && $usersForm->more[3] == 0)
        ) {
            $users->andWhere('tasks.user_id_executor IS NULL');
        }

        if ((isset($usersForm->more[0]) && $usersForm->more[0] == 1)
        || (isset($usersForm->more[1]) && $usersForm->more[1] == 1)
        || (isset($usersForm->more[2]) && $usersForm->more[2] == 1)
        || (isset($usersForm->more[3]) && $usersForm->more[3] == 1)
        ) {
            $users->andWhere('users.date_visit > DATE_SUB(NOW(), INTERVAL 30 MINUTE)');
        }

        if ((isset($usersForm->more[0]) && $usersForm->more[0] == 2)
        || (isset($usersForm->more[1]) && $usersForm->more[1] == 2)
        || (isset($usersForm->more[2]) && $usersForm->more[2] == 2)
        || (isset($usersForm->more[3]) && $usersForm->more[3] == 2)
        ) {
            $users->andWhere(['reviews.user_id_executor' => $userIdExecutor->getUserIdExecutor()]);
        }

        if ((isset($usersForm->more[0]) && $usersForm->more[0] == 3)
        || (isset($usersForm->more[1]) && $usersForm->more[1] == 3)
        || (isset($usersForm->more[2]) && $usersForm->more[2] == 3)
        || (isset($usersForm->more[3]) && $usersForm->more[3] == 3)
        ) {
            $users->andWhere(['users.id' => $userIdExecutor->getFavoritesId()]);
        }

        if (\Yii::$app->request->get('q')) {
            $users->andWhere(['like', 'users.name', $_GET['q']]);
        }

        $users = $users->all();

        return $this->render('index', compact('users', 'usersForm'));
    }
}
