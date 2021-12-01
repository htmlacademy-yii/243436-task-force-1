<?php
namespace frontend\components;

use Yii;
use frontend\models\Cities;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\BadResponseException;
use yii\caching\TagDependency;

class GeoCoder
{
    private $apikey;
    private $uri;

    public function __construct()
    {
        $this->apikey = Yii::$app->params['apikey'];
        $this->uri = Yii::$app->params['uri'];
    }

    /**
     * Получение данных по API Яндекс.Карт
     */
    public function getCityData($q)
    {
        $cach_city = \Yii::$app->cache->get(md5($q));

        if ($cach_city) {
            return json_encode($cach_city);
        }

        $city_id = Cities::find()
            ->select('id')
            ->where(['name' => \Yii::$app->request->post('q')])
            ->one();

        $geocode = \Yii::$app->request->get('geocode', $q);

        $client = new Client([
            'base_uri' => $this->uri,
        ]);

        $request = new Request('GET', '1.x');

        $response = $client->send($request, [
            'query' => ['geocode' => $geocode, 'apikey' => $this->apikey, 'format' => 'json', 'results' => 10]
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
                md5(\Yii::$app->request->post('q')),
                $res,
                86400,
                new TagDependency(['tags' => 'geo'])
            );

            return json_encode($res);
        }
    }
}
