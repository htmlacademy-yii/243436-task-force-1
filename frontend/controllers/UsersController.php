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

        $usersForm->load(\Yii::$app->request->get());

        $users = Users::find()
            ->where(['role' => 'Исполнитель'])
            ->joinWith(['tasksExecutor', 'categories', 'reviewsExecutor', 'favorites'])
            ->orderBy('date_visit DESC');

        if ($usersForm->category) {
            $users->andWhere(['users_and_categories.category_id' => $usersForm->category]);
        }

        if(!(isset($usersForm->more[0])
        && isset($usersForm->more[1])
        && isset($usersForm->more[2])
        && isset($usersForm->more[3]))) {
            if (!empty($usersForm->more)) {
                if (in_array($usersForm::FREE, $usersForm->more)) {
                    $users->andWhere('tasks.user_id_executor IS NULL');
                }
                if (in_array($usersForm::ONLINE, $usersForm->more)) {
                    $users->andWhere('users.date_visit > DATE_SUB(NOW(), INTERVAL 30 MINUTE)');
                }
                if (in_array($usersForm::REVIEWS, $usersForm->more)) {
                    $users->andWhere(['reviews.user_id_executor' => $userIdExecutor->getUserIdExecutor()]);
                }
                if (in_array($usersForm::FAVORITES, $usersForm->more)) {
                    $users->andWhere(['users.id' => $userIdExecutor->getFavoritesId()]);
                }
            }
        }

        if ($usersForm->search) {
            $users->andWhere(['like', 'users.name', $usersForm->search]);
        }

        $users = $users->all();

        return $this->render('index', compact('users', 'usersForm'));
    }
}
