<?php

namespace frontend\models;

use yii\base\Model;

class UsersForm extends Model
{
    public $category = [];

    public $more = [];

    public $search;

    const FREE = 'Сейчас свободен';
    const ONLINE = 'Сейчас онлайн';
    const REVIEWS = 'Есть отзывы';
    const FAVORITES = 'В избранном';

    public function rules()
    {
        return [
            [['category', 'more', 'search'], 'safe'],
        ];
    }

    public function moreList()
    {
        return [
            self::FREE => 'Сейчас свободен',
            self::ONLINE => 'Сейчас онлайн',
            self::REVIEWS => 'Есть отзывы',
            self::FAVORITES => 'В избранном'
        ];
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
