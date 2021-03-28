<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "skills".
 *
 * @property int $id
 * @property string $name
 *
 * @property UsersAndSkills[] $usersAndSkills
 */
class Skills extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'skills';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
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
        ];
    }

    /**
     * Gets query for [[UsersAndSkills]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsersAndSkills()
    {
        return $this->hasMany(UsersAndSkills::class, ['skill_id' => 'id']);
    }
}
