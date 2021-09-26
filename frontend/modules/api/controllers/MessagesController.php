<?php

namespace frontend\modules\api\controllers;

use frontend\models\Messages;
use yii\rest\ActiveController;


class MessagesController extends ActiveController
{
    public $modelClass = Messages::class;

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
