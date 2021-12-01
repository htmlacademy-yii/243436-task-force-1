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
    public static function tableName() : string
    {
        return 'reviews';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() : array
    {
        return [
            [['description'], 'string'],
            ['status', 'required', 'message' => 'Необходимо выбрать статус задания'],
            ['rating', 'required', 'message' => 'Необходимо поставить оценку исполнителю'],
            [['rating', 'user_id_create', 'user_id_executor', 'task_id'], 'integer'],
            [['status'], 'string', 'max' => 20],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::class,
            'targetAttribute' => ['task_id' => 'id']],
            [['user_id_create'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class,
            'targetAttribute' => ['user_id_create' => 'id']],
            [['user_id_executor'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class,
            'targetAttribute' => ['user_id_executor' => 'id']],
            ['task_id', 'default', 'value' => Yii::$app->params['task_current']->id ?? ''],
            ['user_id_executor', 'default', 'value' => Yii::$app->params['task_current']->user_id_executor ?? ''],
            ['user_id_create', 'default', 'value' => Yii::$app->params['task_current']->user_id_create ?? ''],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() : array
    {
        return [
            'id' => 'ID',
            'description' => 'Комментарий',
            'rating' => 'Оценка',
            'user_id_create' => 'User Id Create',
            'user_id_executor' => 'User Id Executor',
            'task_id' => 'Task ID',
            'status' => 'Status',
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
