<?php

namespace frontend\modules\api\controllers;

use frontend\models\Tasks;
use yii\rest\ActiveController;


class TasksController extends ActiveController
{
    public $modelClass = Tasks::class;

    public function behaviors() {
        return [
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'formatParam' =>'_format',
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                    'xml' =>\yii\web\Response::FORMAT_XML,
                ],
            ],
        ];
    }
}
