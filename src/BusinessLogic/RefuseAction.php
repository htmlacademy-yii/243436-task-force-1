<?php

namespace Taskforce\BusinessLogic;

class RefuseAction extends Task
{

    public function getNameAction()
    {
        return parent::allAction()[self::ACTION_REFUSE];
    }

    public function getInsideAction()
    {
        return self::ACTION_REFUSE;
    }

    public function isCompareID($executorID, $customerID)
    {
        if ((int) $executorID !== (int) $customerID) {
            return true;
        } else {
            return false;
        }
    }
}
