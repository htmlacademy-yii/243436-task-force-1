<?php
namespace frontend\controllers;

use frontend\models\Categories;
use frontend\models\Tasks;
use yii\filters\AccessControl;
use yii\web\Controller;

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

    public function init()
    {
        parent::init();
        \Yii::$app->user->loginUrl = ['landing/index'];
    }

    public function actionIndex()
    {
        $this->view->title = 'Создание задания';

        $tasks_form = new Tasks();

        $category = new Categories();

        $category_list = $category->categoryList();

        if(\Yii::$app->request->getIsPost()) {
            $tasks_form->load(\Yii::$app->request->post());

            if ($tasks_form->validate()) {
                $tasks_form->save();
                $task_id = \Yii::$app->db->getLastInsertID();
                $tasks_form->upload($task_id);
                $this->redirect(['tasks/view', 'id' => $task_id]);
            }
        }

        return $this->render('index', compact('tasks_form', 'category_list'));
    }
}
