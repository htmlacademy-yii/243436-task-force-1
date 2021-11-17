<?php
namespace frontend\models;

use Yii;

/**
 * This is the model class for table "clips".
 *
 * @property int $id
 * @property int $user_id_create
 * @property string $path
 *
 * @property Users $userIdCreate
 */
class Clips extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clips';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id'], 'integer'],
            [['path'], 'string', 'max' => 100],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tasks::class,
            'targetAttribute' => ['task_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => 'Path',
            'task_id' => 'Task ID',
        ];
    }

    /**
     * Gets query for [[Clips]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClips()
    {
        return $this->hasMany(Tasks::class, ['task_id' => 'id']);
    }
}
