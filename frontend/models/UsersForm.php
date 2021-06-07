<?php

namespace frontend\models;

use yii\base\Model;

class UsersForm extends Model
{
    public $category;

    public $more;

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
}
