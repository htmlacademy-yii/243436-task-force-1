<?php

namespace Taskforce\BusinessLogic;

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

    public function isCompareID(int $currentID, ?int $executorID, ?int $customerID) : bool
    {
        return $currentID === $executorID;
    }
}
