<?php
namespace app\components;

use frontend\models\Auth;
use frontend\models\Users;
use frontend\models\Cities;
use Taskforce\BusinessLogic\Task;
use Yii;

class AuthHandler
{
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    public function handle()
    {
        if (!Yii::$app->user->isGuest) {
            return;
        }

        $attributes = $this->client->getUserAttributes();

        $auth = $this->findAuth($attributes);

        if ($auth) {
            $user = $auth->user;
            return Yii::$app->user->login($user);
        }

        if (!$auth) {
            $user = $this->createAccount($attributes);

            // $user = Users::findOne(['email' => 'test@mail.ru']); //то создаем новый аккаунт

            return Yii::$app->user->login($user);
        }
    }

    /**
     * Поиск пользователя в таблице auth по id аккаунта провайдера VKontakte
     *
     * $param array $attribute
     * @return Auth
     */
    private function findAuth($attributes)
    {
        $user_id = $attributes['user_id'];

        $params = [
            'source_id' => $user_id,
            'source' => $this->client->getId()
        ];

        return Auth::find()->where($params)->one();
    }

    /**
     * Поиск пользователя в таблице auth по id аккаунта провайдера VKontakte
     *
     * $param array $attribute
     * @return Auth
     */
    private function createAccount($attributes)
    {
        $user_id = $attributes['user_id'];
        $first_name = $attributes['first_name'];
        $city = $attributes['city']['title'];
        $email = $attributes['user_id'].'@mail.ru';

        if ($email !== null
        && Users::find()->where(['email' => $email])->exists()
        || !$email
        || !Cities::find()->where(['name' => $city])->exists()) {
            return;
        }

        $user = $this->createUser($first_name, $city, $email);

        $transaction = \Yii::$app->getDb()->beginTransaction();
        if ($user->save()) {

            $user = $user->getUser();

            $auth = $this->createAuth($user->id, $user_id);
            if ($auth->save()) {
                $transaction->commit();
                return $user;
            }
        }
        $transaction->rollBack();
    }

    private function createUser($first_name, $city, $email)
    {
        $city_id = Cities::find()->where(['name' => $city])->one();

        return new Users([
            'date_add' => Yii::$app->formatter->asDate('now', 'yyyy-MM-dd H:m:s'),
            'email' => $email,
            'name' => $first_name,
            'city_id' => $city_id['id'],
            'address' => $city_id['name'],
            'role' => Task::CREATOR,
            'new_message' => '1',
            'action_task' => '1',
            'new_review' => '1',
            'path' =>'img/avtar.png',
            'password' => Yii::$app->security->generatePasswordHash(Yii::$app->security->generateRandomString())
        ]);
    }

    private function createAuth($userId, $sourceId)
    {
        return new Auth([
            'user_id' => $userId,
            'source' => $this->client->getId(),
            'source_id' => (string) $sourceId
        ]);
    }
}
