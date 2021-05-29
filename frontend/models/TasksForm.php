<?php

namespace frontend\models;

use yii\base\Model;

class TasksForm extends Model
{
    public $category = [];

    public $more = [];

    public $period = [];

    public $search;

    const NOT_EXECUTOR = 'Без исполнителя';
    const DISTANT_WORK = 'Удаленная работа';

    public function rules()
    {
        return [
            [['category', 'more', 'period', 'search'], 'safe'],
        ];
    }

    public function periodList()
    {
        return ['day' => 'За день', 'week' => 'За неделю', 'month' => 'За месяц'];
    }

    public function moreList()
    {
        return [self::NOT_EXECUTOR => 'Без исполнителя', self::DISTANT_WORK => 'Удаленная работа'];
    }
}
