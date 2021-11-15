<?php
namespace frontend\controllers;

use yii\web\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\BadResponseException;
use frontend\models\Cities;
use yii\caching\TagDependency;

class GeoController extends Controller
{
    public function actionIndex()
    {
        if (\Yii::$app->request->post('q')) {

            $city = \Yii::$app->request->post('q');

            $cach_city = \Yii::$app->cache->get($city);

            if ($cach_city) {
                return json_encode($cach_city);
            }

            $city_id = Cities::find()
                ->select('id')
                ->where(['name' => \Yii::$app->request->post('q')])
                ->one();


            $geocode = \Yii::$app->request->get('geocode', $city);
            $apikey = 'e666f398-c983-4bde-8f14-e3fec900592a';


            $client = new Client([
                'base_uri' => 'https://geocode-maps.yandex.ru/',
            ]);


            $request = new Request('GET', '1.x');



            $response = $client->send($request, [
                'query' => ['geocode' => $geocode, 'apikey' => $apikey, 'format' => 'json', 'results' => 10]
            ]);


            if ($response->getStatusCode() !== 200) {
                throw new BadResponseException("Response error: " . $response->getReasonPhrase(), $request, $response);
            }


            $content = $response->getBody()->getContents();


            $response_data = json_decode($content, true);


            if (!empty($response_data['response']['GeoObjectCollection']['featureMember'])) {
                $result = explode(
                    " ",
                    $response_data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos']
                );

                $lat = $result[1];
                $lon = $result[0];

                if (isset($response_data['response']['GeoObjectCollection']['metaDataProperty']
                ['GeocoderResponseMetaData']['suggest'])) {
                    $city = $response_data['response']['GeoObjectCollection']['metaDataProperty']
                    ['GeocoderResponseMetaData']['suggest'];
                } else {
                    $city = $response_data['response']['GeoObjectCollection']['metaDataProperty']
                    ['GeocoderResponseMetaData']['request'];
                }

                $res = [
                    "message" => "Город {$city} найден. Широта: {$lat}, Долгота: {$lon}",
                    "lat" => $lat,
                    "lon" => $lon,
                    "city_id" => $city_id['id']
                ];

                \Yii::$app->cache->set(
                    \Yii::$app->request->post('q'), $res, 86400, new TagDependency(['tags' => 'geo'])
                );

                return json_encode($res);
            }
        }
    }
}
