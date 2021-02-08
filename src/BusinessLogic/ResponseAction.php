<?php

namespace Taskforce\BusinessLogic;

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

    public function isCompareID(int $currentID, ?int $executorID, ?int $customerID) : bool
    {
        return $currentID === $executorID;
    }
}
