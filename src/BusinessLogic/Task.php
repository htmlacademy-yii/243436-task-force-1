<?php

namespace Taskforce\BusinessLogic;

class Task
{
    const STATUS_NEW = 'new';
    const STATUS_CANCEL = 'cancel';
    const STATUS_WORK = 'work';
    const STATUS_PERFORMED = 'performed';
    const STATUS_FAILED = 'failed';

    const ACTION_CANCEL = 'cancel';
    const ACTION_RESPONSE = 'response';
    const ACTION_PERFORMED = 'performed';
    const ACTION_REFUSE = 'refuse';

    public $executor;
    public $customer;

    public function __construct($executor, $customer)
    {
        $this->executor = $executor;
        $this->customer = $customer;
    }

    public function allStatus()
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_CANCEL => 'Отменено',
            self::STATUS_WORK => 'В работе',
            self::STATUS_PERFORMED => 'Выполнено',
            self::STATUS_FAILED => 'Провалено'
        ];
    }

    public function allAction()
    {
        return [
            self::ACTION_CANCEL => 'Отменить',
            self::ACTION_RESPONSE => 'Откликнуться',
            self::ACTION_PERFORMED => 'Выполнено',
            self::ACTION_REFUSE => 'Отказаться'
        ];
    }

    public function getNextStatus(string $action)
    {
        if (self::ACTION_CANCEL === $action) {
            return self::STATUS_CANCEL;
        } elseif (self::ACTION_RESPONSE === $action) {
            return self::STATUS_WORK;
        } elseif (self::ACTION_PERFORMED === $action) {
            return self::STATUS_PERFORMED;
        } else {
            return self::STATUS_FAILED;
        }
    }

    public function getAvailableActions(string $status)
    {
        if (self::STATUS_NEW === $status) {
            return self::ACTION_RESPONSE;
        } elseif (self::STATUS_WORK === $status) {
            return [self::ACTION_PERFORMED, self::ACTION_REFUSE];
        } else {
            return [];
        }
    }
}
