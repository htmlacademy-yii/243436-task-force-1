<?php

namespace Taskforce\BusinessLogic;

abstract class Task
{
    const STATUS_NEW = 'new';
    const STATUS_CANCEL = 'cancel';
    const STATUS_WORK = 'work';
    const STATUS_PERFORMED = 'performed';
    const STATUS_FAILED = 'failed';

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

    abstract function getNameAction();
    abstract function getInsideAction();
    abstract function isCompareID(
        int $currentID,
        int $executorID,
        int $customerID
    );
}
