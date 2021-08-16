<?php

namespace Taskforce\BusinessLogic;

class RefuseAction extends Action
{
    public function getNameAction() : string
    {
        return 'Отказаться';
    }

    public function isCompareID(int $currentID, ?int $executorID, ?int $creatorID) : bool
    {
        return $currentID === $executorID;
    }
}
