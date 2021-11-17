<?php
namespace frontend\models;

use Yii;

/**
 * This is the model class for table "cities".
 *
 * @property int $id
 * @property string $city
 * @property float $lat
 * @property float $lon
 *
 * @property Profiles[] $profiles
 * @property Tasks[] $tasks
 */
class Cities extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'lat', 'lon'], 'required'],
            [['lat', 'lon'], 'number'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'City',
            'lat' => 'Lat',
            'lon' => 'Lon',
        ];
    }

    /**
     * Gets query for [[Profiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(Profiles::class, ['city_id' => 'id']);
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Tasks::class, ['city_id' => 'id']);
    }

    /**
     * @return array возвращает список городов
     */
    public function citiesList()
    {
        $cities_list = [];

        $cities = Cities::find()->select('name')->select('id, name')->indexBy('id')->all();

        foreach ($cities as $key => $city) {
            $cities_list[$key] = $city['name'];
        }

        return $cities_list;
    }
}
