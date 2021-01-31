<?php

namespace Taskforce\BusinessLogic;

class RefuseAction extends Action
{

    public function getNameAction()
    {
        return 'Отказаться';
    }

    public function getInsideAction()
    {
        return 'refuse';
    }

    public function isCompareID($currentID, $executorID, $customerID = null)
    {
        return (int) $currentID === (int) $executorID;
    }
}
