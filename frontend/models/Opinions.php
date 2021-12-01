<?php
namespace frontend\models;

/**
 * This is the model class for table "opinions".
 *
 * @property int $id
 * @property string $date_add
 * @property int $rate
 * @property string $description
 */
class Opinions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName() : string
    {
        return 'opinions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() : array
    {
        return [
            [['date_add', 'rate', 'description'], 'required'],
            [['date_add'], 'safe'],
            [['rate'], 'integer'],
            [['description'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() : array
    {
        return [
            'id' => 'ID',
            'date_add' => 'Date Add',
            'rate' => 'Rate',
            'description' => 'Description',
        ];
    }
}
