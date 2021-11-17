<?php
namespace frontend\models;

use yii\base\Model;

class AuthForm extends Model
{
    public $email;
    public $password;

    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
            [['email'], 'string', 'max' => 72],
            [['password'], 'string', 'max' => 64],
            [['email'], 'validateEmail'],
            [['password'], 'validatePassword'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
        ];
    }

    /**
     * Проверяет пользователя по email в БД
     *
     * @param mixed $attribute
     * @param mixed $params
     *
     * @return array ошибки валидации email
     */
    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user) {
                $this->addError($attribute, 'Неправильный email');
            }
        }
    }

    /**
     * Проверяет совпадение введенного пароля пользователя с паролем в БД
     *
     * @param mixed $attribute
     * @param mixed $params
     *
     * @return array ошибки валидации пароля
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неправильный пароль');
            }
        }
    }

    /**
     * Возвращает пользователя из БД по email
     *
     * @return object данные пользователя по email
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Users::findOne(['email' => $this->email]);
        }

        return $this->_user;
    }
}
