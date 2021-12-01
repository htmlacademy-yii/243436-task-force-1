<?php
namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class UsersForm extends Model
{
    public $category;

    public $more;

    public $search;

    const FREE = 'Сейчас свободен';
    const ONLINE = 'Сейчас онлайн';
    const REVIEWS = 'Есть отзывы';
    const FAVORITES = 'В избранном';

    /**
     * {@inheritdoc}
     */
    public function rules() : array
    {
        return [
            [['category', 'more', 'search'], 'safe'],
        ];
    }

    /**
     * @return array возвращает фильтрацию по исполниетлю
     */
    public function moreList() : array
    {
        return [
            self::FREE => 'Сейчас свободен',
            self::ONLINE => 'Сейчас онлайн',
            self::REVIEWS => 'Есть отзывы',
            self::FAVORITES => 'В избранном'
        ];
    }

    /**
     * Фильтрация и пагинация списка исполнителей
     *
     * @return \yii\data\ActiveDataProvider
     */
    public function filter()
    {
        $userIdExecutor = new Users();

        $users = Users::find()
            ->where(['role' => 'Исполнитель'])
            ->joinWith(['tasksExecutor', 'categories', 'reviewsExecutor', 'favorites'])
            ->distinct()
            ->orderBy('date_visit DESC');

        if ($this->category) {
            $users->andWhere(['users_and_categories.category_id' => $this->category]);
        }

        if (is_array($this->more)) {
            $conditions = [];

            if (in_array($this::FREE, $this->more)) {
                $conditions[] = 'tasks.user_id_executor IS NULL';
            }
            if (in_array($this::ONLINE, $this->more)) {
                $conditions[] = "users.date_visit > unix_timestamp(DATE_SUB(NOW(), INTERVAL 30 MINUTE))";
            }
            if (in_array($this::REVIEWS, $this->more)) {
                $conditions[] = "reviews.user_id_executor IN ({$this->getUserIdExecutor()})";
            }
            if (in_array($this::FAVORITES, $this->more)) {
                $conditions[] = "users.id IN ({$userIdExecutor->getFavoritesId()})";
            }

            if (count($conditions) > 0) {
                $users->andWhere(implode(" or ", $conditions));
            }
        }

        if ($this->search) {
            $users->andWhere(['like', 'users.name', $this->search]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $users,
            'pagination' => [
                'pageSize' => 5,
                'pageSizeParam' => false
            ]
        ]);

        return $dataProvider;
    }
}
