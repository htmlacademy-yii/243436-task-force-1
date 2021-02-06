<?php

use Taskforce\BusinessLogic\CancelAction;
use Taskforce\BusinessLogic\ResponseAction;
use Taskforce\BusinessLogic\PerformedAction;
use Taskforce\BusinessLogic\RefuseAction;
use Taskforce\BusinessLogic\Task;
use Taskforce\Exception\ActionException;
use Taskforce\Exception\StatusException;
use Taskforce\Exception\RoleException;

require_once 'vendor/autoload.php';

$task = new Task('', '');
$cancelAction = new CancelAction();
$responseAction = new ResponseAction();
$performedAction = new PerformedAction();
$refuseAction = new RefuseAction();

try {
    $task->checkStatus($task->allStatus()[Task::STATUS_NEW]);
} catch (StatusException $e) {
    echo die($e->getMessage());
}

try {
    $task->checkAction($task->allAction()[Task::ACTION_CANCEL]);
} catch (ActionException $e) {
    echo die($e->getMessage());
}

try {
    $cancelAction->checkRole(5, 5);
} catch (RoleException $e) {
    echo die($e->getMessage());
}

try {
    $responseAction->checkRole(5, 5);
} catch (RoleException $e) {
    echo die($e->getMessage());
}

try {
    $performedAction->checkRole(5, 5);
} catch (RoleException $e) {
    echo die($e->getMessage());
}

try {
    $refuseAction->checkRole(5, 5);
} catch (RoleException $e) {
    echo die($e->getMessage());
}

assert(
    $task->getNextStatus(Task::ACTION_RESPONSE) === Task::STATUS_WORK,
    'Статус не соответствует действию'
);
assert(
    $task->getNextStatus(Task::ACTION_CANCEL) === Task::STATUS_CANCEL,
    'Статус не соответствует действию'
);
assert(
    $task->getNextStatus(Task::ACTION_PERFORMED) === Task::STATUS_PERFORMED,
    'Статус не соответствует действию'
);
assert(
    $task->getNextStatus(Task::ACTION_REFUSE) === Task::STATUS_FAILED,
    'Статус не соответствует действию'
);


assert(
    $task->getAvailableActions(Task::STATUS_NEW)->isCompareID(5, 5, null)
    && $task->getAvailableActions(Task::STATUS_NEW)->getInsideAction() === Task::ACTION_RESPONSE,
    'Статус не соответствует действию'
);
assert(
    $task->getAvailableActions(Task::STATUS_CANCEL)->isCompareID(5, null, 5)
    && $task->getAvailableActions(Task::STATUS_CANCEL)->getInsideAction() === Task::ACTION_CANCEL,
    'Статус не соответствует действию'
);
assert(
    $task->getAvailableActions(Task::STATUS_WORK)[0]->isCompareID(5, null, 5)
    && $task->getAvailableActions(Task::STATUS_WORK)[0]->getInsideAction() === Task::ACTION_PERFORMED,
    'Статус не соответствует действию'
);
assert(
    $task->getAvailableActions(Task::STATUS_WORK)[1]->isCompareID(5, 5, null)
    && $task->getAvailableActions(Task::STATUS_WORK)[1]->getInsideAction() === Task::ACTION_REFUSE,
    'Статус не соответствует действию'
);
assert(
    $task->getAvailableActions(Task::STATUS_PERFORMED)->isCompareID(5, null, 5)
    && $task->getAvailableActions(Task::STATUS_PERFORMED)->getInsideAction() === Task::ACTION_CANCEL,
    'Статус не соответствует действию'
);
assert(
    $task->getAvailableActions(Task::STATUS_FAILED)->isCompareID(5, null, 5)
    && $task->getAvailableActions(Task::STATUS_FAILED)->getInsideAction() === Task::ACTION_CANCEL,
    'Статус не соответствует действию'
);
