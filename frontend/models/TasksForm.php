<?php
namespace frontend\models;

use yii\base\Model;
use frontend\models\Tasks;
use yii\data\ActiveDataProvider;

class TasksForm extends Model
{
    public $category;

    public $more;

    public $period;

    public $search;

    const NOT_EXECUTOR = 'Без исполнителя';
    const DISTANT_WORK = 'Удаленная работа';
    const DAY = 'day';
    const WEEK = 'week';
    const MONTH = 'month';

    /**
     * {@inheritdoc}
     */
    public function rules() : array
    {
        return [
            [['category', 'more', 'period', 'search'], 'safe'],
        ];
    }

    /**
     * @return array возвращает период
     */
    public function periodList() : array
    {
        return [self::DAY => 'За день', self::WEEK => 'За неделю', self::MONTH => 'За месяц'];
    }

    /**
     * @return array возвращает фильтрацию по исполнителю
     */
    public function moreList() : array
    {
        return [self::NOT_EXECUTOR => 'Без исполнителя', self::DISTANT_WORK => 'Удаленная работа'];
    }

    /**
     * Фильтрация и пагинация списка задач
     *
     * @return \yii\data\ActiveDataProvider
     */
    public function filter()
    {
        $session = \Yii::$app->session;

        if (\Yii::$app->request->get('city')) {
            $session['city_id'] = \Yii::$app->request->get('city');
        }

        $user = Users::find()->where(['id' => \Yii::$app->user->getId()])->one();

        $tasks = Tasks::find()
            ->where(['status' => 'Новое'])
            ->joinWith(['category', 'city', 'creator', 'executor'])
            ->orderBy('date_add DESC');

        if (\Yii::$app->request->get('city') || $session['city_id']) {
            $tasks->andWhere(
                "tasks.city_id is null or tasks.city_id = :city_id",
                [":city_id" => $session['city_id'] ?? $user['city_id']]
            );
        }

        if ($this->category) {
            $tasks->andWhere(['category_id' => $this->category]);
        }

        if (is_array($this->more)) {
            $conditions = [];

            if (in_array($this::NOT_EXECUTOR, $this->more)) {
                $conditions[] = 'user_id_executor IS NULL';
            }
            if (in_array($this::DISTANT_WORK, $this->more)) {
                $conditions[] = 'tasks.city_id IS NULL';
            }

            if (count($conditions) > 0) {
                $tasks->andWhere(implode(" or ", $conditions));
            }
        }

        if ($this->period === $this::DAY) {
            $tasks->andWhere('tasks.date_add BETWEEN CURDATE() AND (CURDATE() + 1)');
        }

        if ($this->period === $this::WEEK) {
            $tasks->andWhere('tasks.date_add >= DATE_SUB(NOW(), INTERVAL 7 DAY)');
        }

        if ($this->period === $this::MONTH) {
            $tasks->andWhere('tasks.date_add >= DATE_SUB(NOW(), INTERVAL 30 DAY)');
        }

        if ($this->search) {
            $tasks->andWhere(['like', 'tasks.name', $this->search]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $tasks,
            'pagination' => [
                'pageSize' => 5,
                'pageSizeParam' => false
            ]
        ]);

        return $dataProvider;
    }
}
