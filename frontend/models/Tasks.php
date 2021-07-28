<?php

namespace frontend\models;

use Yii;


/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $date_add
 * @property int $category_id
 * @property string|null $path
 * @property string $description
 * @property string|null $expire
 * @property string $name
 * @property string|null $address
 * @property int|null $budget
 * @property float|null $lat
 * @property float|null $lon
 * @property int|null $city_id
 * @property int $user_id_create
 * @property int|null $user_id_executor
 * @property string $status
 *
 * @property Categories $category
 * @property Cities $city
 * @property Users $userIdCreate
 * @property Users $userIdExecutor
 */
class Tasks extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'description', 'name'], 'required'],
            [['date_add', 'expire'], 'safe'],
            [['category_id', 'city_id', 'user_id_create', 'user_id_executor'], 'integer'],
            ['budget', 'integer', 'min' => 0],
            [['description'], 'string', 'min' => 30],
            [['lat', 'lon'], 'number'],
            [['path', 'status'], 'string', 'max' => 100],
            [['name'], 'string', 'min' => 10, 'max' => 255],
            [['address'], 'string', 'max' => 700],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' =>
            ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' =>
            ['city_id' => 'id']],
            [['user_id_create'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' =>
            ['user_id_create' => 'id']],
            [['user_id_executor'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' =>
            ['user_id_executor' => 'id']],
            ['date_add', 'default', 'value' => Yii::$app->formatter->asDate('now', 'yyyy-MM-dd H:m:s')], //значение по умолчанию
            ['user_id_create', 'default', 'value' => \Yii::$app->user->getId()], //значение по умолчанию
            ['status', 'default', 'value' => 'Новое'], //значение по умолчанию
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date_add' => 'Date Add',
            'category_id' => 'Категория',
            'path' => 'Path',
            'description' => 'Подробности задания',
            'expire' => 'Сроки исполнения',
            'name' => 'Мне нужно',
            'address' => 'Address',
            'budget' => 'Бюджет',
            'lat' => 'Lat',
            'lon' => 'Lon',
            'city_id' => 'Локация',
            'user_id_create' => 'User Id Create',
            'user_id_executor' => 'User Id Executor',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(Cities::class, ['id' => 'city_id']);
    }

    /**
     * Gets query for [[UserIdCreate]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id_create']);
    }

    /**
     * Gets query for [[UserIdExecutor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id_executor']);
    }
}
