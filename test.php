<?php
/*Тестовый коммент, чтобы создать пулреквест, в следующем задании удалю*/
use Taskforce\BusinessLogic\Task;

require_once 'vendor/autoload.php';

$strategy = new Task('Petr', 'Ivan');

assert(
    $strategy->getNextStatus(Task::ACTION_CANCEL) === Task::STATUS_CANCEL, 'Статус не соответствует действию'
);
assert(
    $strategy->getNextStatus(Task::ACTION_RESPONSE) === Task::STATUS_WORK, 'Статус не соответствует действию'
);
assert(
    $strategy->getNextStatus(Task::ACTION_PERFORMED) === Task::STATUS_PERFORMED, 'Статус не соответствует действию'
);
assert(
    $strategy->getNextStatus(Task::ACTION_REFUSE) === Task::STATUS_FAILED, 'Статус не соответствует действию'
);

assert(
    $strategy->getAvailableActions(Task::STATUS_NEW) === Task::ACTION_RESPONSE, 'Статус не соответствует действию'
);
assert(
    $strategy->getAvailableActions(Task::STATUS_CANCEL) === [], 'Статус не соответствует действию'
);
assert(
    $strategy->getAvailableActions(Task::STATUS_WORK)[0] === Task::ACTION_PERFORMED, 'Статус не соответствует действию'
);
assert(
    $strategy->getAvailableActions(Task::STATUS_WORK)[1] === Task::ACTION_REFUSE, 'Статус не соответствует действию'
);
assert(
    $strategy->getAvailableActions(Task::STATUS_PERFORMED) === [], 'Статус не соответствует действию'
);
assert(
    $strategy->getAvailableActions(Task::STATUS_FAILED) === [], 'Статус не соответствует действию'
);
