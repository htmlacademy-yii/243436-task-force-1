<?php

namespace Taskforce\BusinessLogic;

class PerformedAction extends Task
{

    public function getNameAction()
    {
        return parent::allAction()[self::ACTION_PERFORMED];
    }

    public function getInsideAction()
    {
        return self::ACTION_PERFORMED;
    }

    public function isCompareID($executorID, $customerID)
    {
        if ((int) $executorID === (int) $customerID) {
            return true;
        } else {
            return false;
        }
    }
}
