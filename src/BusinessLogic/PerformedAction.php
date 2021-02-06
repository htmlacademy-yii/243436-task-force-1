<?php

namespace Taskforce\BusinessLogic;
use Taskforce\Exception\RoleException;

class PerformedAction extends Action
{

    public function getNameAction() : string
    {
        return 'Выполнено';
    }

    public function getInsideAction() : string
    {
        return 'performed';
    }

    public function checkRole(int $currentID, int $customerID) : void
    {
        if ($currentID !== $customerID) {
            throw new RoleException("У вас нет прав");
        }
    }

    public function isCompareID($currentID, $executorID, $customerID) : bool
    {
        return $currentID === $customerID;
    }
}
