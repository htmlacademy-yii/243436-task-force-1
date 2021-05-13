<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $password
 * @property string $date_add
 * @property string|null $path
 * @property string|null $date_visit
 * @property string|null $role
 * @property int|null $city_id
 * @property string|null $birthday
 * @property string|null $about
 * @property string|null $phone
 * @property string|null $skype
 *
 * @property Cities $city
 * @property Messages[] $messages
 * @property Messages[] $messages0
 * @property Reviews[] $reviews
 * @property Reviews[] $reviews0
 * @property Tasks[] $tasks
 * @property Tasks[] $tasks0
 * @property UsersAndCategories[] $usersAndCategories
 * @property UsersAndSkills[] $usersAndSkills
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'name', 'password', 'date_add'], 'required'],
            [['date_add', 'date_visit', 'birthday'], 'safe'],
            [['city_id'], 'integer'],
            [['about'], 'string'],
            [['email'], 'string', 'max' => 72],
            [['name', 'role', 'phone', 'skype'], 'string', 'max' => 100],
            [['password'], 'string', 'max' => 64],
            [['path'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' =>
            ['city_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'name' => 'Name',
            'password' => 'Password',
            'date_add' => 'Date Add',
            'path' => 'Path',
            'date_visit' => 'Date Visit',
            'role' => 'Role',
            'city_id' => 'City ID',
            'birthday' => 'Birthday',
            'about' => 'About',
            'phone' => 'Phone',
            'skype' => 'Skype',
        ];
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
     * Gets query for [[MessagesCreator]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessagesCreator()
    {
        return $this->hasMany(Messages::class, ['user_id_create' => 'id']);
    }

    /**
     * Gets query for [[MessagesExecutor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessagesExecutor()
    {
        return $this->hasMany(Messages::class, ['user_id_executor' => 'id']);
    }

    /**
     * Gets query for [[ReviewsCreator]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviewsCreator()
    {
        return $this->hasMany(Reviews::class, ['user_id_create' => 'id']);
    }

    /**
     * Gets query for [[ReviewsCount]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviewsCount()
    {
        return $this->hasMany(Reviews::class, ['user_id_executor' => 'id'])->count();
    }

    /**
     * Gets query for [[TasksCreator]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasksCreator()
    {
        return $this->hasMany(Tasks::class, ['user_id_create' => 'id']);
    }

    /**
     * Gets query for [[TasksCount]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasksCount()
    {
        return $this->hasMany(Tasks::class, ['user_id_executor' => 'id'])->count();
    }

    /**
     * Gets query for [[UsersAndCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Categories::class, ['id' => 'category_id'])
        ->viaTable('users_and_categories', ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UsersAndSkills]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkills()
    {
        return $this->hasMany(Skills::class, ['id' => 'skill_id'])->viaTable('users_and_skills', ['user_id' => 'id']);
    }

    /**
     * Считает среднюю оценку по отзывам.
     *
     * @return string|null возвращает среднюю оценку всех отзывов
     */
    public function getAverageRating()
    {
        $sum_rating = Reviews::find()->where(['user_id_executor' => $this->id])->sum('rating');

        if((int) $sum_rating !== 0) {
            return round($sum_rating/$this->getReviewsCount());
        } else {
            return null;
        }
    }
}
