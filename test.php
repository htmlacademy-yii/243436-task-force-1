<?php
use Taskforce\BusinessLogic\Task;
use Taskforce\BusinessLogic\ResponseAction;
use Taskforce\BusinessLogic\PerformedAction;
use Taskforce\BusinessLogic\RefuseAction;
use Taskforce\BusinessLogic\CancelAction;

require_once 'vendor/autoload.php';

$responseAction = new ResponseAction('', '');
$cancelAction = new CancelAction('', '');
$performedAction = new PerformedAction('', '');
$refuseAction = new RefuseAction('', '');

assert(
    $cancelAction->getNextStatus(Task::ACTION_CANCEL) === Task::STATUS_CANCEL,
    'Статус не соответствует действию'
);
assert(
    $responseAction->getNextStatus(Task::ACTION_RESPONSE) === Task::STATUS_WORK,
    'Статус не соответствует действию'
);
assert(
    $performedAction->getNextStatus(Task::ACTION_PERFORMED) === Task::STATUS_PERFORMED,
    'Статус не соответствует действию'
);
assert(
    $refuseAction->getNextStatus(Task::ACTION_REFUSE) === Task::STATUS_FAILED,
    'Статус не соответствует действию'
);


assert(
    $responseAction->getAvailableActions(Task::STATUS_NEW)->isCompareID(5, 3)
    && $responseAction->getAvailableActions(Task::STATUS_NEW)->getInsideAction() === Task::ACTION_RESPONSE,
    'Статус не соответствует действию'
);
assert(
    $cancelAction->getAvailableActions(Task::STATUS_CANCEL)->isCompareID(5, 5)
    && $cancelAction->getAvailableActions(Task::STATUS_CANCEL)->getInsideAction() === Task::ACTION_CANCEL,
    'Статус не соответствует действию'
);
assert(
    $performedAction->getAvailableActions(Task::STATUS_WORK)[0]->isCompareID(5, 5)
    && $performedAction->getAvailableActions(Task::STATUS_WORK)[0]->getInsideAction() === Task::ACTION_PERFORMED,
    'Статус не соответствует действию'
);
assert(
    $refuseAction->getAvailableActions(Task::STATUS_WORK)[1]->isCompareID(5, 3)
    && $refuseAction->getAvailableActions(Task::STATUS_WORK)[1]->getInsideAction() === Task::ACTION_REFUSE,
    'Статус не соответствует действию'
);
assert(
    $cancelAction->getAvailableActions(Task::STATUS_PERFORMED)->isCompareID(5, 5)
    && $cancelAction->getAvailableActions(Task::STATUS_PERFORMED)->getInsideAction() === Task::ACTION_CANCEL,
    'Статус не соответствует действию'
);
assert(
    $cancelAction->getAvailableActions(Task::STATUS_FAILED)->isCompareID(5, 5)
    && $cancelAction->getAvailableActions(Task::STATUS_FAILED)->getInsideAction() === Task::ACTION_CANCEL,
    'Статус не соответствует действию'
);
