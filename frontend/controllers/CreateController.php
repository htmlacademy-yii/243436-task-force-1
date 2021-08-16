<?php
namespace frontend\controllers;

use frontend\models\Categories;
use frontend\models\Clips;
use frontend\models\Tasks;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\UploadedFile;

class CreateController extends Controller
{
    public function behaviors()
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
            ]
        ];
    }

    public function actionIndex()
    {

        $this->view->title = 'Создание задания';

        $tasks_form = new Tasks();

        $category = new Categories();

        $category_list = $category->categoryList();

        $session = \Yii::$app->session;

        $task_id = '';

        $clips = '';

        if(\Yii::$app->request->getIsPost()) {
            $tasks_form->load(\Yii::$app->request->post());

            if ($tasks_form->validate()) {
                $tasks_form->save();
                $task_id = $tasks_form->id;

                if (isset($session['images']) && !empty($session['images'])) {
                    foreach($session['images'] as $image) {
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

        return $this->render('index', compact('tasks_form', 'category_list'));
    }

    public function actionUpload() {

        $images = \Yii::$app->session['images'] ?? [];

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
