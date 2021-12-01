<?php
namespace frontend\models;

use Yii;

/**
 * This is the model class for table "messages".
 *
 * @property int $id
 * @property string $message
 * @property int|null $user_id_create
 * @property int|null $user_id_executor
 * @property int|null $task_id
 * @property string|null $date_add
 *
 * @property Tasks $task
 * @property Users $userIdCreate
 * @property Users $userIdExecutor
 */
class Messages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName() : string
    {
        return 'messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() : array
    {
        return [
            [['message'], 'required'],
            [['message'], 'string'],
            [['user_id_create', 'user_id_executor', 'task_id'], 'integer'],
            [['date_add'], 'safe'],
            [['user_id_create'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class,
            'targetAttribute' => ['user_id_create' => 'id']],
            [['user_id_executor'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class,
            'targetAttribute' => ['user_id_executor' => 'id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::class,
            'targetAttribute' => ['task_id' => 'id']],
            ['date_add', 'default', 'value' => Yii::$app->formatter->asDate('now', 'yyyy-MM-dd H:m:s')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() : array
    {
        return [
            'id' => 'ID',
            'message' => 'Текст сообщения',
            'user_id_create' => 'User Id Create',
            'user_id_executor' => 'User Id Executor',
            'task_id' => 'Task ID',
            'date_add' => 'Date Add',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function fields() : array
    {
        return [
            'id',
            'message',
            'user_id_create',
            'user_id_executor',
            'task_id',
            'published_at' => 'date_add',
            'is_mine' => function () {
                return $this->user_id_create === Yii::$app->user->id || $this->user_id_executor === Yii::$app->user->id;
            },
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
    public function getExcecutor()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id_executor']);
    }
}
