<?php

namespace frontend\models;

use yii\base\Model;

class ChangePasswordForm extends Model
{
    public $password;
    public $password_new;
    public $password_repeat;

    private $_user;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'password_new', 'password_repeat'], 'string', 'max' => 64],
            [['password'], 'validatePassword'],
            [['password'], 'validatePasswordTwo', 'skipOnEmpty' => false],
            [['password_new'], 'validatePasswordFour', 'skipOnEmpty' => false],
            [['password_new'], 'validatePasswordFive', 'skipOnEmpty' => false],
            [['password_repeat'], 'validatePasswordOne'],
            [['password_repeat'], 'validatePasswordThree', 'skipOnEmpty' => false],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'password' => 'Текущий пароль',
            'password_new' => 'Новый пароль',
            'password_repeat' => 'Повтор пароля',
        ];
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
            $user = Users::find()->where(['id' => \Yii::$app->user->getId()])->one();

            if (!$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неправильный текущий пароль');
            }
        }
    }

    /**
     * Проверяет совпадение повторно введенного пароля с новым
     *
     * @param mixed $attribute
     * @param mixed $params
     *
     * @return array ошибки валидации пароля
     */
    public function validatePasswordOne($attribute, $params)
    {
        if (!$this->hasErrors()) {

            if ($this->password_repeat !== $this->password_new) {
                $this->addError($attribute, 'Повтор пароля не совпадает');
            }
        }
    }

    /**
     * Проверяет совпадение наличие текущего пароля для смены на новый
     *
     * @param mixed $attribute
     * @param mixed $params
     *
     * @return array ошибки валидации пароля
     */
    public function validatePasswordTwo($attribute, $params)
    {
        if (!$this->hasErrors()) {

            if (!$this->password
            && ($this->password_new
            && $this->password_repeat
            || $this->password_new
            || $this->password_repeat)) {
                $this->addError($attribute, 'Не указан текущий пароль');
            }

        }
    }

    /**
     * Проверяет наличие повтора нового пароля
     *
     * @param mixed $attribute
     * @param mixed $params
     *
     * @return array ошибки валидации пароля
     */
    public function validatePasswordThree($attribute, $params)
    {
        if (!$this->hasErrors()) {

            if ($this->password
            && $this->password_new
            && !$this->password_repeat) {
                $this->addError($attribute, 'Не указан повтор нового пароля');
            }

        }
    }

    /**
     * Проверяет наличие нового пароля
     *
     * @param mixed $attribute
     * @param mixed $params
     *
     * @return array ошибки валидации пароля
     */
    public function validatePasswordFour($attribute, $params)
    {
        if (!$this->hasErrors()) {

            if ($this->password
            && !$this->password_new
            && $this->password_repeat) {
                $this->addError($attribute, 'Не указан новый пароль');
            }

        }
    }

    /**
     * Проверяет наличие нового и повтора нового пароля
     *
     * @param mixed $attribute
     * @param mixed $params
     *
     * @return array ошибки валидации пароля
     */
    public function validatePasswordFive($attribute, $params)
    {
        if (!$this->hasErrors()) {

            if ($this->password
            && !$this->password_new
            && !$this->password_repeat) {
                $this->addError($attribute, 'Не указан новый пароль');
            }

        }
    }

    /**
     * Возвращает пользователя из БД по id
     *
     * @return object данные пользователя по id
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Users::findOne(['id' => \Yii::$app->user->getId()]);
        }

        return $this->_user;
    }
}
