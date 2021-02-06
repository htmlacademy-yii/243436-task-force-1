<?php

namespace Taskforce\BusinessLogic;
use Taskforce\Exception\StatusException;
use Taskforce\Exception\ActionException;

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

    public function __construct(string $executor,string $customer)
    {
        $this->executor = $executor;
        $this->customer = $customer;
    }

    public function allStatus() : array
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_CANCEL => 'Отменено',
            self::STATUS_WORK => 'В работе',
            self::STATUS_PERFORMED => 'Выполнено',
            self::STATUS_FAILED => 'Провалено'
        ];
    }

    public function checkStatus (string $status) : void
    {
        if (!in_array($status, $this->allStatus())) {
            throw new StatusException("Задан неверный статус");
        }
    }

    public function allAction() : array
    {
        return [
            self::ACTION_CANCEL => 'Отменить',
            self::ACTION_RESPONSE => 'Откликнуться',
            self::ACTION_PERFORMED => 'Выполнено',
            self::ACTION_REFUSE => 'Отказаться'
        ];
    }

    public function checkAction (string $action) : void
    {
        if (!in_array($action, $this->allAction())) {
            throw new ActionException("Действие недоступно");
        }
    }

    public function getNextStatus(string $action) : string
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
            return new ResponseAction('', '');
        } elseif (self::STATUS_WORK === $status) {
            return [
                new PerformedAction('', ''),
                new RefuseAction('', '')
            ];
        } else {
            return new CancelAction('', '');
        }
    }
}
