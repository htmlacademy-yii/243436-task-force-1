<?php

namespace frontend\components;

use frontend\models\Cities;
use yii\base\Widget;
use frontend\models\Users;

class CityWidget extends Widget
{
    public function run()
    {
        $cities = Cities::find()->select('id, name')->all();

        $user = Users::find()->where(['id' => \Yii::$app->user->getId()])->one();

        $session = \Yii::$app->session;

        return $this->render('city', compact('cities', 'user', 'session'));
    }
}
