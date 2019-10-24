<?php

namespace Htmlacademy;

require_once 'vendor/autoload.php';

use DateTime;
use Htmlacademy\Models\Task;
use Htmlacademy\Actions\Apply;
use Htmlacademy\Actions\Assign;
use Htmlacademy\Actions\Cancel;
use Htmlacademy\Actions\Complete;
use Htmlacademy\Actions\Decline;
use Htmlacademy\Actions\Message;

$ownerId = 1;
$agentId = 2;
$passerbyId = 42;
$tomorrow = new DateTime('tomorrow');
$yesterday = new DateTime('yesterday');

//assert for getNextStatusForAction
$task = new Task($ownerId, $tomorrow);
assert($task->getNextStatusForAction(Apply::class) === TaskStatuses::STATUS_NEW, 'apply action');
assert($task->getNextStatusForAction(Message::class) === $task->getStatus(), 'message action');
assert($task->getNextStatusForAction(Assign::class) === TaskStatuses::STATUS_ACTIVE, 'assign action');
assert($task->getNextStatusForAction(Cancel::class) === TaskStatuses::STATUS_CANCELLED, 'cancel action');
assert($task->getNextStatusForAction(Decline::class) === TaskStatuses::STATUS_FAILED, 'decline action');
assert($task->getNextStatusForAction(Complete::class) === TaskStatuses::STATUS_COMPLETED,
    'complete action');

//assert for getActionsForUser NEW task
$task2 = new Task($ownerId, $tomorrow);
assert($task2->getActionsForUser($ownerId) === [Cancel::class, Assign::class],
    'owner action(s) for new task');
assert($task2->getActionsForUser($passerbyId) === [Apply::class], 'passerby action(s) for new task');

//assert for getActionsForUser ACTIVE task
$task2->changeStatusToActive(Assign::class, $ownerId, $agentId);
assert($task2->getActionsForUser($ownerId) === [Complete::class], 'owner action(s) for active task');
assert($task2->getActionsForUser($agentId) === [Decline::class], 'agent action(s) for active task');
assert($task2->getActionsForUser($passerbyId) === [], 'passerby action(s) for active task');

//assert for getActionsForUser COMPLETED task
$task2->changeStatusToCompleted(Complete::class, $ownerId);
assert($task2->getActionsForUser($ownerId) === [], 'owner action(s) for completed task');
assert($task2->getActionsForUser($agentId) === [], 'agent action(s) for completed task');
assert($task2->getActionsForUser($passerbyId) === [], 'passerby action(s) for completed task');

//assert for getActionsForUser CANCELLED task
$task3 = new Task($ownerId, $tomorrow);
$task3->changeStatusToCancelled(Cancel::class, $ownerId);
assert($task3->getActionsForUser($ownerId) === [], 'owner action(s) for cancelled task');
assert($task3->getActionsForUser($agentId) === [], 'agent action(s) for cancelled task');
assert($task3->getActionsForUser($passerbyId) === [], 'passerby action(s) for cancelled task');

//assert for getActionsForUser FAILED task
$task4 = new Task($ownerId, $tomorrow);
$task4->changeStatusToActive(Assign::class, $ownerId, $agentId);
$task4->changeStatusToFailed(Decline::class, $agentId);
assert($task4->getActionsForUser($ownerId) === [], 'owner action(s) for failed task');
assert($task4->getActionsForUser($agentId) === [], 'agent action(s) for failed task');
assert($task4->getActionsForUser($passerbyId) === [], 'passerby action(s) for failed task');

//assert for getActionsForUser EXPIRED task
$task5 = new Task($ownerId, $yesterday);
$task5->changeStatusToExpired();
assert($task5->getActionsForUser($ownerId) === [], 'owner action(s) for expired task');
assert($task5->getActionsForUser($agentId) === [], 'agent action(s) for expired task');
assert($task5->getActionsForUser($passerbyId) === [], 'passerby action(s) for expired task');
