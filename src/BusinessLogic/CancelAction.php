<?php

namespace Taskforce\BusinessLogic;

class CancelAction extends Action
{

    public function getNameAction()
    {
        return parent::allAction()[self::ACTION_CANCEL];
    }

    public function getInsideAction()
    {
        return 'cancel';
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
