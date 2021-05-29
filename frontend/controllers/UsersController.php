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
            if ((array) $usersForm::FREE === $usersForm->more) {
                $users->andWhere('tasks.user_id_executor IS NULL');
            } elseif ((array) $usersForm::ONLINE === $usersForm->more) {
                $users->andWhere('users.date_visit > DATE_SUB(NOW(), INTERVAL 30 MINUTE)');
            } elseif ((array) $usersForm::REVIEWS === $usersForm->more) {
                $users->andWhere("reviews.user_id_executor IN ({$userIdExecutor->getUserIdExecutor()})");
            } elseif ((array) $usersForm::FAVORITES === $usersForm->more) {
                $users->andWhere("users.id IN ({$userIdExecutor->getFavoritesId()})");
            } elseif ([$usersForm::FREE, $usersForm::ONLINE] === $usersForm->more) {
                $users->andWhere("
                    tasks.user_id_executor IS NULL
                    OR users.date_visit > DATE_SUB(NOW(), INTERVAL 30 MINUTE)
                ");
            } elseif ([$usersForm::FREE, $usersForm::REVIEWS] === $usersForm->more) {
                $users->andWhere("
                    tasks.user_id_executor IS NULL
                    OR reviews.user_id_executor IN ({$userIdExecutor->getUserIdExecutor()})
                ");
            } elseif ([$usersForm::FREE, $usersForm::FAVORITES] === $usersForm->more) {
                $users->andWhere("
                    tasks.user_id_executor IS NULL
                    OR users.id IN ({$userIdExecutor->getFavoritesId()})
                ");
            } elseif ([$usersForm::FREE, $usersForm::ONLINE, $usersForm::REVIEWS] === $usersForm->more) {
                $users->andWhere("
                    tasks.user_id_executor IS NULL
                    OR users.date_visit > DATE_SUB(NOW(), INTERVAL 30 MINUTE)
                    OR reviews.user_id_executor IN ({$userIdExecutor->getUserIdExecutor()})
                ");
            } elseif ([$usersForm::FREE, $usersForm::REVIEWS, $usersForm::FAVORITES] === $usersForm->more) {
                $users->andWhere("
                    tasks.user_id_executor IS NULL
                    OR reviews.user_id_executor IN ({$userIdExecutor->getUserIdExecutor()})
                    OR users.id IN ({$userIdExecutor->getFavoritesId()})
                ");
            } elseif ([$usersForm::ONLINE, $usersForm::REVIEWS] === $usersForm->more) {
                $users->andWhere("
                    users.date_visit > DATE_SUB(NOW(), INTERVAL 30 MINUTE)
                    OR reviews.user_id_executor IN ({$userIdExecutor->getUserIdExecutor()})
                ");
            } elseif ([$usersForm::ONLINE, $usersForm::FAVORITES] === $usersForm->more) {
                $users->andWhere("
                    users.date_visit > DATE_SUB(NOW(), INTERVAL 30 MINUTE)
                    OR users.id IN ({$userIdExecutor->getFavoritesId()})
                ");
            } elseif ([$usersForm::ONLINE, $usersForm::REVIEWS, $usersForm::FAVORITES] === $usersForm->more) {
                $users->andWhere("
                    users.date_visit > DATE_SUB(NOW(), INTERVAL 30 MINUTE)
                    OR reviews.user_id_executor IN ({$userIdExecutor->getUserIdExecutor()})
                    OR users.id IN ({$userIdExecutor->getFavoritesId()})
                ");
            } elseif ([$usersForm::REVIEWS, $usersForm::FAVORITES] === $usersForm->more) {
                $users->andWhere("
                    reviews.user_id_executor IN ({$userIdExecutor->getUserIdExecutor()})
                    OR users.id IN ({$userIdExecutor->getFavoritesId()})
                ");
            }
        }

        if ($usersForm->search) {
            $users->andWhere(['like', 'users.name', $usersForm->search]);
        }

        $users = $users->all();

        return $this->render('index', compact('users', 'usersForm', 'categories'));
    }
}
