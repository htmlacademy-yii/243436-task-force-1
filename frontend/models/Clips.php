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
            [['user_id_create', 'path'], 'required'],
            [['user_id_create'], 'integer'],
            [['path'], 'string', 'max' => 100],
            [['user_id_create'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class,
            'targetAttribute' => ['user_id_create' => 'id']],
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
            'path' => 'Path',
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
