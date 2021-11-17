<?php
namespace frontend\controllers;

use frontend\models\Users;
use frontend\models\UsersForm;
use frontend\models\Categories;
use frontend\models\Favorites;
use frontend\models\PhotoWork;
use frontend\models\Reviews;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use Taskforce\BusinessLogic\Task;

class UsersController extends SecuredController
{
    /**
     * Рендерит страницу index
     *
     * @return mixed
     */
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
            ->distinct()
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
                $conditions[] = "users.date_visit > unix_timestamp(DATE_SUB(NOW(), INTERVAL 30 MINUTE))";
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

        $dataProvider = new ActiveDataProvider([
            'query' => $users,
            'pagination' => [
                'pageSize' => 5,
                'pageSizeParam' => false
            ]
        ]);

        $current_time = \Yii::$app->formatter->asTimestamp('now');

        return $this->render('index', compact('usersForm', 'categories', 'dataProvider', 'current_time'));
    }

    /**
     * Рендерит страницу user
     *
     * @return mixed
     */
    public function actionUser($id)
    {
        $users = Users::find()
            ->where(['users.id' => $id])
            ->one();

        if ($users->role === Task::CREATOR) {
            throw new \yii\web\HttpException(404, 'Запрошенная страница не найдена');
        } else {
            $role_current_user = Users::find()
                ->where(['users.id' => \Yii::$app->user->getId()])
                ->one();

            if (empty($users)) {
                throw new NotFoundHttpException('Страница не найдена...');
            }

            $photo_work = PhotoWork::find()
                ->where(['user_id' => $id])
                ->all();

            $reviews = Reviews::find()
                ->where(['user_id_executor' => $id])
                ->all();

            $this->view->title = $users['name'];

            $now_time = time();
            $birthday_time = strtotime($users->birthday);
            $years_old = floor(($now_time - $birthday_time) / 31536000);

            $favorite = Favorites::find()
                ->where(['user_id_create' => 1, 'user_id_executor' => \Yii::$app->request->get('id')])
                ->one();

            $favorites = new Favorites;

            $favorites->user_id_create = 1;
            $favorites->user_id_executor = \Yii::$app->request->get('id');

            $favorite_link = '';

            if ($favorite === null) {
                $favorite_link = Url::to(['users/user', 'id' => \Yii::$app->request->get('id'), 'favorite' => 'true']);
                if (\Yii::$app->request->get('favorite') === 'true') {
                    if ($favorites->save()) {
                        $this->redirect(['users/user', 'id' => \Yii::$app->request->get('id'), 'favorite' => 'true']);
                    }
                }
            } else {
                $favorite_link = Url::to(['users/user', 'id' => \Yii::$app->request->get('id'), 'favorite' => 'false']);
                if (\Yii::$app->request->get('favorite') === 'false') {
                    if ($favorite->delete()) {
                        $this->redirect(['users/user', 'id' => \Yii::$app->request->get('id'), 'favorite' => 'false']);
                    }
                }
            }
        }

        return $this->render(
            'user',
            compact(
                'users',
                'years_old',
                'photo_work',
                'reviews',
                'favorite',
                'favorite_link',
                'role_current_user'
            )
        );
    }
}
