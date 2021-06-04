<?php

namespace frontend\controllers;

use frontend\models\Users;
use frontend\models\UsersForm;
use frontend\models\Categories;
use yii\web\Controller;

class UsersController extends Controller
{
    public function actionIndex()
    {
        $this->view->title = 'Исполнители';

        $usersForm = new UsersForm();

        $userIdExecutor = new Users();

        $categories = new Categories();

        $usersForm->load(\Yii::$app->request->get());

        $users = Users::find()
            ->where(['role' => 'Исполнитель'])
            ->joinWith(['tasksExecutor', 'categories', 'reviewsExecutor', 'favorites'])
            ->orderBy('date_visit DESC');

        if ($usersForm->category) {
            $users->andWhere(['users_and_categories.category_id' => $usersForm->category]);
        }

        if (is_array($usersForm->more)) {

            $conditions = [];

            if (in_array($usersForm::FREE, $usersForm->more)) {
                $conditions[] = 'tasks.user_id_executor IS NULL';
            }
            if (in_array($usersForm::ONLINE, $usersForm->more)) {
                $conditions[] = 'users.date_visit > DATE_SUB(NOW(), INTERVAL 30 MINUTE)';
            }
            if (in_array($usersForm::REVIEWS, $usersForm->more)) {
                $conditions[] = "reviews.user_id_executor IN ({$userIdExecutor->getUserIdExecutor()})";
            }
            if (in_array($usersForm::FAVORITES, $usersForm->more)) {
                $conditions[] = "users.id IN ({$userIdExecutor->getFavoritesId()})";
            }

            if (count($conditions) > 0) {
                $users->andWhere(implode(" or ", $conditions));
            }
        }

        if ($usersForm->search) {
            $users->andWhere(['like', 'users.name', $usersForm->search]);
        }

        $users = $users->all();

        return $this->render('index', compact('users', 'usersForm', 'categories'));
    }
}
