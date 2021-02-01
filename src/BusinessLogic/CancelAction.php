<?php

namespace Taskforce\BusinessLogic;

class CancelAction extends Action
{

    public function getNameAction()
    {
        return 'Отменить';
    }

    public function getInsideAction()
    {
        return 'cancel';
    }

    public function isCompareID($currentID, $executorID = null, $customerID)
    {
        return (int) $currentID === (int) $customerID;
    }
}
