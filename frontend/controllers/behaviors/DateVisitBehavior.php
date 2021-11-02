<?php

namespace frontend\controllers\behaviors;

use frontend\models\Users;
use Yii\base\Behavior;
use Yii\web\Controller;

class DateVisitBehavior extends Behavior
{
    public function events()
    {
        return [
            Controller::EVENT_AFTER_ACTION => 'currentDate'
        ];
    }

    public function currentDate()
    {
        if (!\Yii::$app->user->isGuest) {
            $user = Users::find()->where(['id' => \Yii::$app->user->getId()])->one();

            $user->date_visit = \Yii::$app->formatter->asTimestamp('now');

            $user->save();

            return $user;
        }
    }
}
