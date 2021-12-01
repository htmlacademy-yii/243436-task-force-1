<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;

class GeoController extends Controller
{
    /**
     * Получение данных по API Яндекс.Карт
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (\Yii::$app->request->post('q')) {

            $geoCoder = Yii::$app->geoCoder;

            $test = $geoCoder->getCityData(\Yii::$app->request->post('q'));

            return $test;
        }
    }
}
