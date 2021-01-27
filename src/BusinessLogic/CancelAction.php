<?php

namespace Taskforce\BusinessLogic;

class CancelAction extends Task
{

    public function getNameAction()
    {
        return parent::allAction()[self::ACTION_CANCEL];
    }

    public function getInsideAction()
    {
        return self::ACTION_CANCEL;
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
