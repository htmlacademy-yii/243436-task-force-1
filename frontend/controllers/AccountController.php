<?php
namespace frontend\controllers;

use frontend\models\Users;
use yii\web\UploadedFile;
use frontend\models\Categories;
use frontend\models\TasksForm;
use frontend\models\UsersAndCategories;
use frontend\models\ChangePasswordForm;
use frontend\models\PhotoWork;
use Taskforce\BusinessLogic\Task;

class AccountController extends SecuredController
{
    /**
     * Рендерит страницу index
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $this->view->title = 'Настройки профиля';

        $categories = new Categories();

        $usersAndCategories = new UsersAndCategories();

        $changePassword = new ChangePasswordForm();

        $usersAndCategoriesList = $usersAndCategories->usersAndCategoriesList(\Yii::$app->user->getId());

        $tasksForm = new TasksForm();

        $photoWorkList = PhotoWork::find()
            ->where(['user_id' => \Yii::$app->user->getId()])
            ->all();

        $user = Users::find()
            ->where(['users.id' => \Yii::$app->user->getId()])
            ->one();

        $session = \Yii::$app->session;

        if (\Yii::$app->request->getIsPost()) {
            $user->load(\Yii::$app->request->post());
            $tasksForm->load(\Yii::$app->request->post());
            $changePassword->load(\Yii::$app->request->post());

            $user->avatar = UploadedFile::getInstance($user, 'avatar');

            if ($user->validate() && $changePassword->validate()) {
                $user->upload();

                if ($changePassword->password_repeat) {
                    $user->password = \Yii::$app->security->generatePasswordHash($changePassword->password_repeat);
                }

                if (!$tasksForm->category) {
                    $tasksForm->category = [];
                    $user->role = Task::CREATOR;
                } else {
                    $user->role = Task::EXECUTOR;
                }

                $user->save();

                if (!empty($tasksForm->category)) {
                    foreach ($tasksForm->category as $category) {
                        $categories_list = Categories::find()->where(['id' => $category])->one();

                        if (!in_array($category, $usersAndCategoriesList)) {
                            $user->link('categories', $categories_list);
                        }
                    }
                }

                if (!empty(array_diff($usersAndCategoriesList, $tasksForm->category))) {
                    foreach (array_diff($usersAndCategoriesList, $tasksForm->category) as $value) {
                        $list = UsersAndCategories::find()->where(['category_id' => $value])->one();
                        $list->delete();
                    }
                }

                if (isset($session['images']) && !empty($session['images'])) {
                    $photoWorkCount = PhotoWork::find()->where(['user_id' => \Yii::$app->user->getId()])->count();

                    if ($photoWorkCount) {
                        for ($i = 0; $i < $photoWorkCount; $i++) {
                            $photoWorkList[$i]->delete();
                        }
                    }

                    foreach ($session['images'] as $image) {
                        $photoWork = new PhotoWork();

                        $photoWork->path = $image;
                        $photoWork->user_id = $user->id;

                        $photoWork->save();
                    }

                    $session->remove('images');
                }

                $this->redirect(['account/index']);
            }
        }

        if (!empty($user->getErrors()) || !empty($changePassword->getErrors())) {
            \Yii::$app->session['errors'] = $user->getErrors();
            \Yii::$app->session['errors_password'] = $changePassword->getErrors();
        } else {
            \Yii::$app->session->remove('errors');
            \Yii::$app->session->remove('errors_password');
        }

        return $this->render('index', compact('user', 'categories', 'tasksForm', 'changePassword', 'photoWorkList'));
    }

    /**
     * Загрузка картинок в папку upload
     */
    public function actionUpload()
    {
        $images = \Yii::$app->session['images'] ?? [];

        if (isset(\Yii::$app->session['errors']) || isset(\Yii::$app->session['errors'])) {
            $images = [];
        }

        if ($files = UploadedFile::getInstancesByName('photo')) {
            foreach ($files as $file) {
                $newname = uniqid('upload') . '.' . $file->getExtension();

                $file->saveAs('@webroot/uploads/' . $newname);

                $images[] = $newname;
            }

            \Yii::$app->session['images'] = $images;
        }
    }
}
