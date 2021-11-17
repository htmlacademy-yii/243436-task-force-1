<?php
namespace frontend\models;

use Yii;

/**
 * This is the model class for table "favorites".
 *
 * @property int $id
 * @property int $user_id_create
 * @property int $user_id_executor
 *
 * @property Users $userIdCreate
 * @property Users $userIdExecutor
 */
class Favorites extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'favorites';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id_create', 'user_id_executor'], 'required'],
            [['user_id_create', 'user_id_executor'], 'integer'],
            [['user_id_create'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class,
            'targetAttribute' => ['user_id_create' => 'id']],
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
            'user_id_create' => 'User Id Create',
            'user_id_executor' => 'User Id Executor',
        ];
    }

    /**
     * Gets query for [[UserIdCreate]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserIdCreate()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id_create']);
    }

    /**
     * Gets query for [[UserIdExecutor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserIdExecutor()
    {
        return $this->hasOne(Users::class, ['id' => 'user_id_executor']);
    }
}
