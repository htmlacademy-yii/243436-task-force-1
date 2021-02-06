<?php

namespace Taskforce\BusinessLogic;
use Taskforce\Exception\RoleException;

class RefuseAction extends Action
{

    public function getNameAction() : string
    {
        return 'Отказаться';
    }

    public function getInsideAction() : string
    {
        return 'refuse';
    }

    public function checkRole(int $currentID, int $executorID) : void
    {
        if ($currentID !== $executorID) {
            throw new RoleException("У вас нет прав");
        }
    }

    public function isCompareID($currentID, $executorID, $customerID) : bool
    {
        return $currentID === $executorID;
    }
}
