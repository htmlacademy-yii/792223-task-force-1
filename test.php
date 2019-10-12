<?php

namespace Htmlacademy;

require_once 'vendor/autoload.php';

use Htmlacademy\Models\Task;

$ownerId = 1;
$agentId = 2;
$passerbyId = 42;
$tomorrow = 'tomorrow';
$yesterday = 'yesterday';

//assert for getNextStatusForAction
$task = new Task($ownerId, $tomorrow);
assert($task->getNextStatusForAction(TaskActions::ACTION_APPLY) === TaskStatuses::STATUS_NEW, 'apply action');
assert($task->getNextStatusForAction(TaskActions::ACTION_MESSAGE) === $task->getStatus(), 'message action');
assert($task->getNextStatusForAction(TaskActions::ACTION_ASSIGN) === TaskStatuses::STATUS_ACTIVE, 'assign action');
assert($task->getNextStatusForAction(TaskActions::ACTION_CANCEL) === TaskStatuses::STATUS_CANCELLED, 'cancel action');
assert($task->getNextStatusForAction(TaskActions::ACTION_DECLINE) === TaskStatuses::STATUS_FAILED, 'decline action');
assert($task->getNextStatusForAction(TaskActions::ACTION_COMPLETE) === TaskStatuses::STATUS_COMPLETED,
    'complete action');

//assert for getActionsForUser NEW task
$task2 = new Task($ownerId, $tomorrow);
assert($task2->getActionsForUser($ownerId) === [TaskActions::ACTION_CANCEL, TaskActions::ACTION_ASSIGN],
    'owner action(s) for new task');
assert($task2->getActionsForUser($passerbyId) === [TaskActions::ACTION_APPLY], 'passerby action(s) for new task');

//assert for getActionsForUser ACTIVE task
$task2->changeStatusToActive(TaskActions::ACTION_ASSIGN, $ownerId, $agentId);
assert($task2->getActionsForUser($ownerId) === [TaskActions::ACTION_COMPLETE], 'owner action(s) for active task');
assert($task2->getActionsForUser($agentId) === [TaskActions::ACTION_DECLINE], 'agent action(s) for active task');
assert($task2->getActionsForUser($passerbyId) === [], 'passerby action(s) for active task');

//assert for getActionsForUser COMPLETED task
$task2->changeStatusToCompleted(TaskActions::ACTION_COMPLETE, $ownerId);
assert($task2->getActionsForUser($ownerId) === [], 'owner action(s) for completed task');
assert($task2->getActionsForUser($agentId) === [], 'agent action(s) for completed task');
assert($task2->getActionsForUser($passerbyId) === [], 'passerby action(s) for completed task');

//assert for getActionsForUser CANCELLED task
$task3 = new Task($ownerId, $tomorrow);
$task3->changeStatusToCancelled(TaskActions::ACTION_CANCEL, $ownerId);
assert($task3->getActionsForUser($ownerId) === [], 'owner action(s) for cancelled task');
assert($task3->getActionsForUser($agentId) === [], 'agent action(s) for cancelled task');
assert($task3->getActionsForUser($passerbyId) === [], 'passerby action(s) for cancelled task');

//assert for getActionsForUser FAILED task
$task4 = new Task($ownerId, $tomorrow);
$task4->changeStatusToActive(TaskActions::ACTION_ASSIGN, $ownerId, $agentId);
$task4->changeStatusToFailed(TaskActions::ACTION_DECLINE, $agentId);
assert($task4->getActionsForUser($ownerId) === [], 'owner action(s) for failed task');
assert($task4->getActionsForUser($agentId) === [], 'agent action(s) for failed task');
assert($task4->getActionsForUser($passerbyId) === [], 'passerby action(s) for failed task');

//assert for getActionsForUser EXPIRED task
$task5 = new Task($ownerId, $yesterday);
$task5->changeStatusToExpired();
assert($task5->getActionsForUser($ownerId) === [], 'owner action(s) for expired task');
assert($task5->getActionsForUser($agentId) === [], 'agent action(s) for expired task');
assert($task5->getActionsForUser($passerbyId) === [], 'passerby action(s) for expired task');
