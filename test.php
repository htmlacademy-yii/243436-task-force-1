<?php

use Taskforce\BusinessLogic\Task;
use Taskforce\Exception\ActionException;
use Taskforce\Exception\StatusException;

require_once 'vendor/autoload.php';

$task = new Task('', '');

try {
    $task->getAvailableActions(Task::STATUS_NEW);
} catch (StatusException $e) {
    echo die($e->getMessage());
}

try {
    $task->getAvailableActions(Task::STATUS_CANCEL);
} catch (StatusException $e) {
    echo die($e->getMessage());
}

try {
    $task->getAvailableActions(Task::STATUS_WORK);
} catch (StatusException $e) {
    echo die($e->getMessage());
}

try {
    $task->getAvailableActions(Task::STATUS_PERFORMED);
} catch (StatusException $e) {
    echo die($e->getMessage());
}

try {
    $task->getAvailableActions(Task::STATUS_FAILED);
} catch (StatusException $e) {
    echo die($e->getMessage());
}

try {
    $task->getNextStatus(Task::ACTION_CANCEL);
} catch (ActionException $e) {
    echo die($e->getMessage());
}

try {
    $task->getNextStatus(Task::ACTION_RESPONSE);
} catch (ActionException $e) {
    echo die($e->getMessage());
}

try {
    $task->getNextStatus(Task::ACTION_PERFORMED);
} catch (ActionException $e) {
    echo die($e->getMessage());
}

try {
    $task->getNextStatus(Task::ACTION_REFUSE);
} catch (ActionException $e) {
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
