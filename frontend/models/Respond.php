<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "respond".
 *
 * @property int $id
 * @property int $task_id
 * @property int $user_id_executor
 * @property string|null $comment
 * @property string|null $date
 * @property int|null $budget
 *
 * @property Tasks $task
 * @property Users $userIdExecutor
 */
class Respond extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'respond';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'user_id_executor'], 'required'],
            [['task_id', 'user_id_executor', 'budget'], 'integer'],
            [['comment'], 'string'],
            [['date'], 'safe'],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::class,
            'targetAttribute' => ['task_id' => 'id']],
            [['user_id_executor'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class,
            'targetAttribute' => ['user_id_executor' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'user_id_executor' => 'User Id Executor',
            'comment' => 'Comment',
            'date' => 'Date',
            'budget' => 'Budget',
        ];
    }

    /**
     * Gets query for [[Task]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Tasks::class, ['id' => 'task_id']);
    }

    /**
     * Gets query for [[Executor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id_executor']);
    }
}
