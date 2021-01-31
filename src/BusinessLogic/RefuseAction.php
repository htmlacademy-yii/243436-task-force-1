<?php

namespace Taskforce\BusinessLogic;

class RefuseAction extends Action
{

    public function getNameAction()
    {
        return parent::allAction()[self::ACTION_REFUSE];
    }

    public function getInsideAction()
    {
        return 'refuse';
    }

    public function isCompareID($currentID, $executorID, $customerID)
    {
        if ($currentID === $executorID) {
            return (int) $executorID !== (int) $customerID;
        } else {
            return false;
        }
    }
}
