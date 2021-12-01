<?php
namespace frontend\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use frontend\controllers\behaviors\DateVisitBehavior;

abstract class SecuredController extends Controller
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
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
            DateVisitBehavior::class
        ];
    }
}
