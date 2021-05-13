<?php

namespace frontend\models;

use yii\base\Model;

class TasksForm extends Model
{
    public $category = [];

    public $more = [];

    public $period = [];

    public $search;

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
        return [1 => 'Без исполнителя', 2 => 'Удаленная работа'];
    }

    public function categoryList()
    {
        $i = 0;
        $j = 0;

        $id = [];
        $name = [];

        $categoryId = Categories::find()->select('id')->asArray()->all();
        $categoryName = Categories::find()->select('name')->asArray()->all();

        foreach($categoryId as $category) {
            foreach($category as $value) {
                $id[$i] = $value;
                $i++;
            }
        }

        foreach($categoryName as $category) {
            foreach($category as $value) {
                $name[$j] = $value;
                $j++;
            }
        }

        $category = array_combine($id, $name);

        return $category;
    }
}
