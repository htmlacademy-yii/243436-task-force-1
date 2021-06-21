<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "reviews".
 *
 * @property int $id
 * @property string $review
 * @property int $rating
 * @property int $user_id_create
 * @property int $user_id_executor
 *
 * @property Users $userIdCreate
 * @property Users $userIdExecutor
 */
class Reviews extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reviews';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['review', 'rating', 'user_id_create', 'user_id_executor'], 'required'],
            [['review'], 'string'],
            [['rating', 'user_id_create', 'user_id_executor'], 'integer'],
            [['user_id_create'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' =>
            ['user_id_create' => 'id']],
            [['user_id_executor'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' =>
            ['user_id_executor' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'review' => 'Review',
            'rating' => 'Rating',
            'user_id_create' => 'User Id Create',
            'user_id_executor' => 'User Id Executor',
        ];
    }

    /**
     * Gets query for [[UserIdCreate]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id_create']);
    }

    /**
     * Gets query for [[UserIdExecutor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id_executor']);
    }

    /**
     * Gets query for [[Task].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Tasks::class, ['id' => 'task_id']);
    }
}
