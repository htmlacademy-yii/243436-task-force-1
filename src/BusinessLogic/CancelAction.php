<?php

namespace Taskforce\BusinessLogic;

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

    public function isCompareID(int $currentID, ?int $executorID, ?int $customerID) : bool
    {
        return $currentID === $customerID;
    }
}
