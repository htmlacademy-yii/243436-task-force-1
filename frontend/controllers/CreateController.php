<?php
namespace frontend\controllers;

use frontend\models\Categories;
use frontend\models\Clips;
use frontend\models\Tasks;
use yii\filters\AccessControl;
use frontend\controllers\behaviors\DateVisitBehavior;
use yii\web\Controller;
use yii\web\UploadedFile;

class CreateController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors() : array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => false,
                        'roles' => ['?']
                    ],
                    [
                        'allow' => false,
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->identity->role != 'Заказчик';
                        }
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
            DateVisitBehavior::class
        ];
    }

    /**
     * Рендерит страницу index
     *
     * @return mixed
     */
    public function actionIndex()
    {

        $this->view->title = 'Создание задания';

        $tasks_form = new Tasks();

        $category = new Categories();

        $category_list = $category->categoryList();

        $session = \Yii::$app->session;

        if (\Yii::$app->request->getIsPost()) {
            $tasks_form->load(\Yii::$app->request->post());

            if ($tasks_form->validate()) {
                $tasks_form->save();
                $task_id = $tasks_form->id;

                if (isset($session['images']) && !empty($session['images'])) {
                    foreach ($session['images'] as $image) {
                        $clips = new Clips();

                        $clips->path = $image;
                        $clips->task_id = $task_id;

                        $clips->save();
                    }

                    $session->remove('images');
                }

                $this->redirect(['tasks/view', 'id' => $task_id]);
            }
        }

        if (!empty($tasks_form->getErrors())) {
            \Yii::$app->session['errors'] = $tasks_form->getErrors();
        } else {
            \Yii::$app->session->remove('errors');
        }

        return $this->render('index', compact('tasks_form', 'category_list'));
    }

    /**
     * Загрузка картинок в папку upload
     */
    public function actionUpload()
    {
        $images = \Yii::$app->session['images'] ?? [];

        if (isset(\Yii::$app->session['errors'])) {
            $images = [];
        }

        if ($files = UploadedFile::getInstancesByName('clips')) {
            foreach ($files as $file) {
                $newname = uniqid('upload') . '.' . $file->getExtension();

                $file->saveAs('@webroot/uploads/' . $newname);

                $images[] = $newname;
            }

            \Yii::$app->session['images'] = $images;
        }
    }
}
