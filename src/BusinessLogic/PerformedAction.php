<?php

namespace Taskforce\BusinessLogic;

class PerformedAction extends Action
{

    public function getNameAction()
    {
        return parent::allAction()[self::ACTION_PERFORMED];
    }

    public function getInsideAction()
    {
        return 'performed';
    }

    public function isCompareID($currentID, $executorID, $customerID)
    {
        if ($currentID !== $executorID) {
            return (int) $executorID === (int) $customerID;
        } else {
            return false;
        }
    }
}
