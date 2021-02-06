<?php

namespace Taskforce\BusinessLogic;
use Taskforce\Exception\RoleException;

class CancelAction extends Action
{

    public function getNameAction() : string
    {
        return 'Отменить';
    }

    public function getInsideAction() : string
    {
        return 'cancel';
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
