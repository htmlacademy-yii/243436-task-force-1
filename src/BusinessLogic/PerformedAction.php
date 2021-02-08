<?php

namespace Taskforce\BusinessLogic;

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

    public function isCompareID(int $currentID, ?int $executorID, ?int $customerID) : bool
    {
        return $currentID === $customerID;
    }
}
