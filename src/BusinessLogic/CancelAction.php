<?php

namespace Taskforce\BusinessLogic;

class CancelAction extends Action
{
    public function getNameAction() : string
    {
        return 'Отменить';
    }

    public function isCompareID(int $currentID, ?int $executorID, ?int $creatorID) : bool
    {
        return $currentID === $creatorID;
    }
}
