<?php

namespace Taskforce\BusinessLogic;

class ResponseAction extends Action
{
    /**
     * @return string Возвращает действие оотклика
     */
    public function getNameAction() : string
    {
        return 'Откликнуться';
    }

    /**
     * @param integer $currentID id текущего пользователя
     * @param integer $executorID id пользователя исполнителя
     * @param integer $creatorID id пользователя создателя
     *
     * @return bool Возвращает true или false, если id текущего пользователя равено id пользователя создателя
     */
    public function isCompareID(int $currentID, ?int $executorID, ?int $creatorID) : bool
    {
        return $currentID === $executorID;
    }
}
