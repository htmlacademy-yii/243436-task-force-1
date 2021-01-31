<?php

namespace Taskforce\BusinessLogic;

class ResponseAction extends Action
{
    public function getNameAction()
    {
        return 'Откликнуться';
    }

    public function getInsideAction()
    {
        return 'response';
    }

    public function isCompareID($currentID, $executorID, $customerID = null)
    {
        return (int) $currentID === (int) $executorID;
    }
}
