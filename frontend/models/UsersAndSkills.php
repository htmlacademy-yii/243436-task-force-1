<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "users_and_skills".
 *
 * @property int $id
 * @property int $user_id
 * @property int $skill_id
 *
 * @property Skills $skill
 * @property Users $user
 */
class UsersAndSkills extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_and_skills';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'skill_id'], 'required'],
            [['user_id', 'skill_id'], 'integer'],
            [['skill_id'], 'exist', 'skipOnError' => true, 'targetClass' => Skills::class, 'targetAttribute' =>
            ['skill_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' =>
            ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'skill_id' => 'Skill ID',
        ];
    }

    /**
     * Gets query for [[Skill]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSkill()
    {
        return $this->hasOne(Skills::class, ['id' => 'skill_id']);
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
}
