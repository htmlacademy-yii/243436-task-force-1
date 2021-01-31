<?php
use Taskforce\BusinessLogic\Task;
use Taskforce\BusinessLogic\Action;
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
    $responseAction->getNextStatus(Action::ACTION_RESPONSE) === Task::STATUS_WORK,
    'Статус не соответствует действию'
);
assert(
    $cancelAction->getNextStatus(Action::ACTION_CANCEL) === Task::STATUS_CANCEL,
    'Статус не соответствует действию'
);
assert(
    $performedAction->getNextStatus(Action::ACTION_PERFORMED) === Task::STATUS_PERFORMED,
    'Статус не соответствует действию'
);
assert(
    $refuseAction->getNextStatus(Action::ACTION_REFUSE) === Task::STATUS_FAILED,
    'Статус не соответствует действию'
);


assert(
    $responseAction->getAvailableActions(Task::STATUS_NEW)->isCompareID(5, 5, 3)
    && $responseAction->getAvailableActions(Task::STATUS_NEW)->getInsideAction() === Action::ACTION_RESPONSE,
    'Статус не соответствует действию'
);
assert(
    $cancelAction->getAvailableActions(Task::STATUS_CANCEL)->isCompareID(4, 5, 5)
    && $cancelAction->getAvailableActions(Task::STATUS_CANCEL)->getInsideAction() === Action::ACTION_CANCEL,
    'Статус не соответствует действию'
);
assert(
    $performedAction->getAvailableActions(Task::STATUS_WORK)[0]->isCompareID(4, 5, 5)
    && $performedAction->getAvailableActions(Task::STATUS_WORK)[0]->getInsideAction() === Action::ACTION_PERFORMED,
    'Статус не соответствует действию'
);
assert(
    $refuseAction->getAvailableActions(Task::STATUS_WORK)[1]->isCompareID(5, 5, 3)
    && $refuseAction->getAvailableActions(Task::STATUS_WORK)[1]->getInsideAction() === Action::ACTION_REFUSE,
    'Статус не соответствует действию'
);
assert(
    $cancelAction->getAvailableActions(Task::STATUS_PERFORMED)->isCompareID(4, 5, 5)
    && $cancelAction->getAvailableActions(Task::STATUS_PERFORMED)->getInsideAction() === Action::ACTION_CANCEL,
    'Статус не соответствует действию'
);
assert(
    $cancelAction->getAvailableActions(Task::STATUS_FAILED)->isCompareID(4, 5, 5)
    && $cancelAction->getAvailableActions(Task::STATUS_FAILED)->getInsideAction() === Action::ACTION_CANCEL,
    'Статус не соответствует действию'
);
