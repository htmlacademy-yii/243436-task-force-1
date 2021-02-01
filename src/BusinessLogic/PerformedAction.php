<?php

namespace Taskforce\BusinessLogic;

class PerformedAction extends Action
{

    public function getNameAction()
    {
        return 'Выполнено';
    }

    public function getInsideAction()
    {
        return 'performed';
    }

    public function isCompareID($currentID, $executorID = null, $customerID)
    {
        return (int) $currentID === (int) $customerID;
    }
}
