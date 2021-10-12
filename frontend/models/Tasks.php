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
            [['address'], 'validateAddress'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' =>
            ['category_id' => 'id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' =>
            ['city_id' => 'id']],
            [['user_id_create'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' =>
            ['user_id_create' => 'id']],
            [['user_id_executor'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' =>
            ['user_id_executor' => 'id']],
            ['date_add', 'default', 'value' => Yii::$app->formatter->asDate('now', 'yyyy-MM-dd H:m:s')],
            ['user_id_create', 'default', 'value' => \Yii::$app->user->getId()],
            ['status', 'default', 'value' => 'Новое'],
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
            'address' => 'Локация',
            'budget' => 'Бюджет',
            'lat' => 'Lat',
            'lon' => 'Lon',
            'city_id' => 'Сity_id',
            'user_id_create' => 'User Id Create',
            'user_id_executor' => 'User Id Executor',
            'status' => 'Status',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'published_at' => 'date_add',
            'category_id',
            'path',
            'description',
            'expire',
            'title' => 'name',
            'address',
            'budget',
            'lat',
            'lon',
            'city_id',
            'author_name' => 'user_id_create',
            'user_id_executor',
            'status',
            'new_messages' => function () {
                return Messages::find()->where(['task_id' => $this->id])->count();
            },
        ];
    }

    /**
     * Проверяет город из БД
     *
     * @param mixed $attribute
     * @param mixed $params
     *
     * @return array ошибки валидации адреса
     */
    public function validateAddress($attribute, $params)
    {
        $address = Cities::find()
            ->where(['name' => $this->address])
            ->one();

        if (!$address) {
            $this->addError($attribute, 'Сервис не работает в данном регионе');
        }

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

    /**
     * Gets query for [[Responds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponds()
    {
        return $this->hasMany(Respond::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Clips]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClips()
    {
        return $this->hasMany(Clips::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Messages::class, ['task_id' => 'id']);
    }

    /**
     * Gets query for [[Reviews]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviews()
    {
        return $this->hasMany(Reviews::class, ['task_id' => 'id']);
    }
}
