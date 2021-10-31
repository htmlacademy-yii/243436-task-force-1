<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "users_and_categories".
 *
 * @property int $user_id
 * @property int $category_id
 *
 * @property Categories $category
 * @property Users $user
 */
class UsersAndCategories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_and_categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'category_id'], 'required'],
            [['user_id', 'category_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' =>
            ['user_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' =>
            ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'category_id' => 'Category ID',
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id']);
    }

    /**
     * @return array возвращает список категорий
     */
    public function UsersAndCategoriesList($id)
    {
        $user_id_list = [];

        $categories = UsersAndCategories::find()->select('category_id')->where(['user_id' => $id])->asArray()->all();

        if ($categories) {
            foreach($categories as $key => $category) {
                $user_id_list[$key] = $category['category_id'];
            }
        }

        return $user_id_list;
    }
}
