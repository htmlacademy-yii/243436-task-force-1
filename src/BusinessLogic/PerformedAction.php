<?php

namespace Taskforce\BusinessLogic;

class PerformedAction extends Action
{
    public function getNameAction() : string
    {
        return 'Завершить';
    }

    public function isCompareID(int $currentID, ?int $executorID, ?int $creatorID) : bool
    {
        return $currentID === $creatorID;
    }
}
