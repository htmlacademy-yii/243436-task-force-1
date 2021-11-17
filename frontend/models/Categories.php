<?php
namespace frontend\models;

use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property string $name
 * @property string $icon
 *
 * @property Tasks[] $tasks
 * @property UsersAndCategories[] $usersAndCategories
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'icon'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['icon'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'icon' => 'Icon',
        ];
    }

    /**
     * Gets query for [[Tasks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Tasks::class, ['category_id' => 'id']);
    }

    /**
     * Gets query for [[UsersAndCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsersAndCategories()
    {
        return $this->hasMany(UsersAndCategories::class, ['category_id' => 'id']);
    }

    /**
     * @return array возвращает список категорий
     */
    public function categoryList()
    {
        $category_list = [];

        $categories = Categories::find()->select('id, name')->indexBy('id')->asArray()->all();

        foreach ($categories as $key => $category) {
            $category_list[$key] = $category['name'];
        }

        return $category_list;
    }
}
