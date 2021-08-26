<?php

namespace Taskforce\BusinessLogic;
use Taskforce\Exception\StatusException;

class Task
{
    const STATUS_NEW = 'Новое';
    const STATUS_WORK = 'В работе';
    const STATUS_FAILED = 'Провалено';
    const STATUS_PERFORMED = 'Выполнено';
    const STATUS_CANCEL = 'Отменено';

    const ACTION_RESPONSE = 'Откликнуться';
    const ACTION_REFUSE = 'Отказаться';
    const ACTION_PERFORMED = 'Завершить';
    const ACTION_CANCEL = 'Отменить';

    const EXECUTOR = 'Исполнитель';
    const CREATOR = 'Заказчик';

    public function checkStatus (string $status) : void
    {
        $statusList = ['Новое', 'В работе', 'Провалено', 'Выполнено', 'Отменено'];

        if (!in_array($status, $statusList)) {
            throw new StatusException("Задан неверный статус");
        }
    }

    public function getAvailableActions(string $status)
    {
        $this->checkStatus($status);

        if (self::STATUS_NEW === $status
        || self::STATUS_FAILED === $status
        || self::STATUS_PERFORMED === $status
        || self::STATUS_CANCEL === $status) {
            return [
                new ResponseAction(),
                new CancelAction()
            ];
        } elseif (self::STATUS_WORK === $status) {
            return [
                new PerformedAction(),
                new RefuseAction()
            ];
        }
    }
}
