<?php

namespace frontend\controllers;

use frontend\models\Users;
use frontend\models\UsersForm;
use frontend\models\Categories;
use frontend\models\Favorites;
use frontend\models\PhotoWork;
use frontend\models\Reviews;
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

    public function actionUser($id)
    {
        $users = Users::find()
            ->where(['users.id' => $id])
            ->one();

        $photo_work = PhotoWork::find()
            ->where(['user_id' => $id])
            ->all();

        $reviews = Reviews::find()
            ->where(['user_id_executor' => $id])
            ->all();

        $reviewsCount = Reviews::find()
            ->where(['user_id_executor' => $id])
            ->count();

        $favorites = Favorites::find()
            ->where(['user_id_create' => 1])
            ->indexBy('user_id_executor')
            ->asArray()
            ->all();

        $executors = [];
        $i = 0;

        foreach($favorites as $key => $value) {
            $executors[$i] = $key;
            $i++;
        }

        $this->view->title = $users['name'];

        $now_time = time();
        $birthday_time = strtotime($users->birthday);
        $years_old = floor(($now_time - $birthday_time) / 31536000);

        return $this->render(
            'user', compact('users', 'years_old', 'photo_work', 'reviews', 'reviewsCount', 'executors')
        );
    }

    public function actionUpdate()
    {
        $favorites_list = Favorites::find()
            ->where(['user_id_create' => 1])
            ->indexBy('user_id_executor')
            ->asArray()
            ->all();

        $executors = [];
        $i = 0;

        foreach($favorites_list as $key => $value) {
            $executors[$i] = $key;
            $i++;
        }

        $favorites = new Favorites;

        $favorites->user_id_create = 1;
        $favorites->user_id_executor = \Yii::$app->request->get('id');

        if (!in_array(\Yii::$app->request->get('id'), $executors)) {
            if($favorites->save()) {
                return $this->redirect(['users/user', 'id' => \Yii::$app->request->get('id')]);
            }
        } else {
            $favorite = Favorites::find()
            ->where(['user_id_create' => 1, 'user_id_executor' => \Yii::$app->request->get('id')])
            ->one();

            if($favorite->delete()) {
                return $this->redirect(['users/user', 'id' => \Yii::$app->request->get('id')]);
            }
        }

        return $this->render('update');
    }
}
