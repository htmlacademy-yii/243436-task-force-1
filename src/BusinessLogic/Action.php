<?php

namespace Taskforce\BusinessLogic;

abstract class Action extends Task
{
    const ACTION_CANCEL = 'cancel';
    const ACTION_RESPONSE = 'response';
    const ACTION_PERFORMED = 'performed';
    const ACTION_REFUSE = 'refuse';

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
}
