<?php
namespace Taskforce\BusinessLogic;

abstract class Action
{
    abstract function getNameAction() : string;
    abstract function isCompareID(int $currentID, ?int $executorID, ?int $creatorID) : bool;
}
