<?php

namespace Taskforce\BusinessLogic;

class ResponseAction extends Action
{

    public function getNameAction()
    {
        return parent::allAction()[self::ACTION_RESPONSE];
    }

    public function getInsideAction()
    {
        return 'response';
    }

    public function isCompareID($currentID, $executorID, $customerID)
    {
        if ($currentID === $executorID) {
            return $executorID !== $customerID;
        } else {
            return false;
        }
    }
}
