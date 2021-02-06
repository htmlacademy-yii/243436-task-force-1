<?php

namespace Taskforce\BusinessLogic;
use Taskforce\Exception\RoleException;

class ResponseAction extends Action
{
    public function getNameAction() : string
    {
        return 'Откликнуться';
    }

    public function getInsideAction() : string
    {
        return 'response';
    }

    public function checkRole(int $currentID, int $customerID) : void
    {
        if ($currentID !== $customerID) {
            throw new RoleException("У вас нет прав");
        }
    }

    public function isCompareID($currentID, $executorID, $customerID) : bool
    {
        return $currentID === $executorID;
    }
}
