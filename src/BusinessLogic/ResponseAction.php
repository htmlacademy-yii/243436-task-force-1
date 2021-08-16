<?php

namespace Taskforce\BusinessLogic;

class ResponseAction extends Action
{
    public function getNameAction() : string
    {
        return 'Откликнуться';
    }

    public function isCompareID(int $currentID, ?int $executorID, ?int $creatorID) : bool
    {
        return $currentID === $executorID;
    }
}
