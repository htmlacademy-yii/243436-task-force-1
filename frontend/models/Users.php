<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

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
class Users extends ActiveRecord implements IdentityInterface
{
    public $avatar;
    private $_user;

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id) : IdentityInterface|null
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId() : int|string
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        // return $this->authKey;
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        // return $this->authKey === $authKey;
    }

    /**
     * @param string $password
     * @return bool if password is valid for current user
     */
    public function validatePassword($password) : bool
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() : string
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() : array
    {
        return [
            [['email', 'name', 'password'], 'required'],
            [['date_add', 'date_visit', 'birthday'], 'safe'],
            [['city_id'], 'integer'],
            [['address'], 'string', 'max' => 700],
            [['new_message', 'action_task', 'new_review', 'show_contacts', 'show_profile'], 'string', 'max' => 10],
            [['address'], 'validateAddress'],
            [['about'], 'string'],
            [['email'], 'string', 'max' => 72],
            [['name', 'role'], 'string', 'max' => 100],
            [['phone'], 'string', 'min' => 11, 'max' => 11],
            [['skype'], 'string', 'min' => 3],
            [['messenger'], 'string', 'min' => 1],
            [['password'], 'string', 'max' => 64],
            [['path'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cities::class, 'targetAttribute' =>
            ['city_id' => 'id']],
            ['email', 'email'],
            ['email', 'unique'],
            ['date_add', 'default', 'value' => Yii::$app->formatter->asDate('now', 'yyyy-MM-dd H:m:s')],
            [['avatar'], 'image']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() : array
    {
        return [
            'id' => 'ID',
            'email' => 'Электронная почта',
            'name' => 'Ваше имя',
            'password' => 'Пароль',
            'date_add' => 'Date Add',
            'path' => 'Path',
            'date_visit' => 'Date Visit',
            'role' => 'Role',
            'city_id' => 'Город проживания',
            'birthday' => 'Birthday',
            'about' => 'About',
            'phone' => 'Телефон',
            'skype' => 'Skype',
            'avatar' => 'Сменить аватар',
            'address' => 'Локация',
            'messenger' => 'Другой месседжер',
            'new_message' => 'Новое сообщение',
            'action_task' => 'Действия по заданию',
            'new_review' => 'Новый отзыв',
            'show_contacts' => 'Показывать мои контакты только заказчику',
            'show_profile' => 'Не показывать мой профиль'
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
     * Gets query for [[Auths]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuths()
    {
        return $this->hasMany(Auth::class, ['user_id' => 'id']);
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
     * Gets query for [[PhotoWorks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPhotoWorks()
    {
        return $this->hasMany(PhotoWork::class, ['user_id' => 'id']);
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
     * Gets query for [[ReviewsCount]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReviewsExecutor()
    {
        return $this->hasMany(Reviews::class, ['user_id_executor' => 'id']);
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
     * Gets query for [[TasksExecutor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasksExecutor()
    {
        return $this->hasMany(Tasks::class, ['user_id_executor' => 'id']);
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
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Categories::class, ['id' => 'category_id'])
        ->viaTable('users_and_categories', ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Favorites]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFavorites()
    {
        return $this->hasMany(Favorites::class, ['user_id_create' => 'id', 'user_id_executor' => 'id']);
    }

    /**
     * Считает среднюю оценку по отзывам.
     *
     * @return string|null возвращает среднюю оценку всех отзывов
     */
    public function getAverageRating() : string|null
    {
        $reviews = Reviews::find()->where(['user_id_executor' => $this->id])->all();

        $count_reviews = count($reviews);

        $rating = [];
        $i = 0;

        foreach ($reviews as $review) {
            $rating[$i] = $review['rating'];
            $i++;
        }

        $sum_reviews = array_sum($rating);

        $average_rating = '';

        if ((int) $sum_reviews >= 1) {
            $average_rating = number_format($sum_reviews/$count_reviews, 2, '.', '');
        }
        return $average_rating;
    }

    /**
     * Возвращает id исполнителей, у которых есть отзывы.
     *
     * @return string id исполнителей, у которых есть отзывы
     */
    public function getUserIdExecutor() : int|string
    {
        $id = Reviews::find()->select('user_id_executor')->distinct()->column();

        $executor = 0;

        if ($id) {
            $executor = implode(",", $id);
        }

        return $executor;
    }

    /**
     * Возвращает id исполнителей, которые находятся в избранном
     *
     * @return string id исполнителей, которые находятся в избранном
     */
    public function getFavoritesId() : int|string
    {
        $id = Favorites::find()->select('user_id_executor')->distinct()->column();

        $executor = 0;

        if ($id) {
            $executor = implode(",", $id);
        }

        return $executor;
    }

    /**
     * Возвращает пользователя из БД по email
     *
     * @return mixed данные пользователя по email
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Users::findOne(['email' => $this->email]);
        }

        return $this->_user;
    }

    /**
     * Сохраняет файл в нужную директорию и присваивает имя файлу в данной модели
     */
    public function upload()
    {
        if ($this->avatar && $this->validate()) {
            $newname = uniqid('upload') . '.' . $this->avatar->getExtension();

            $this->avatar->saveAs('@webroot/uploads/' . $newname, false);

            $this->path = 'uploads/'.$newname;
        }
    }
}
