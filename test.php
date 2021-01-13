<?php

use Taskforce\BusinessLogic\Task;

require_once 'vendor/autoload.php';

$strategy = new Task('Petr', 'Ivan');

assert($strategy->getNextStatus(Task::ACTION_REFUSE) == Task::STATUS_FAILED, 'Статус не соответствует действию');

assert($strategy->getAvailableActions(Task::STATUS_FAILED) == null, 'Статус не соответствует действию');
