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
assert($task->getNextStatusForAction(Apply::getName()) === TaskStatuses::STATUS_NEW, 'apply action');
assert($task->getNextStatusForAction(Message::getName()) === $task->getStatus(), 'message action');
assert($task->getNextStatusForAction(Assign::getName()) === TaskStatuses::STATUS_ACTIVE, 'assign action');
assert($task->getNextStatusForAction(Cancel::getName()) === TaskStatuses::STATUS_CANCELLED, 'cancel action');
assert($task->getNextStatusForAction(Decline::getName()) === TaskStatuses::STATUS_FAILED, 'decline action');
assert($task->getNextStatusForAction(Complete::getName()) === TaskStatuses::STATUS_COMPLETED,
    'complete action');

//assert for getActionsForUser NEW task
$task2 = new Task($ownerId, $tomorrow);
assert($task2->getActionsForUser($ownerId) === [Cancel::getName(), Assign::getName()],
    'owner action(s) for new task');
assert($task2->getActionsForUser($passerbyId) === [Apply::getName()], 'passerby action(s) for new task');

//assert for getActionsForUser ACTIVE task
$task2->changeStatusToActive(Assign::getName(), $ownerId, $agentId);
assert($task2->getActionsForUser($ownerId) === [Complete::getName()], 'owner action(s) for active task');
assert($task2->getActionsForUser($agentId) === [Decline::getName()], 'agent action(s) for active task');
assert($task2->getActionsForUser($passerbyId) === [], 'passerby action(s) for active task');

//assert for getActionsForUser COMPLETED task
$task2->changeStatusToCompleted(Complete::getName(), $ownerId);
assert($task2->getActionsForUser($ownerId) === [], 'owner action(s) for completed task');
assert($task2->getActionsForUser($agentId) === [], 'agent action(s) for completed task');
assert($task2->getActionsForUser($passerbyId) === [], 'passerby action(s) for completed task');

//assert for getActionsForUser CANCELLED task
$task3 = new Task($ownerId, $tomorrow);
$task3->changeStatusToCancelled(Cancel::getName(), $ownerId);
assert($task3->getActionsForUser($ownerId) === [], 'owner action(s) for cancelled task');
assert($task3->getActionsForUser($agentId) === [], 'agent action(s) for cancelled task');
assert($task3->getActionsForUser($passerbyId) === [], 'passerby action(s) for cancelled task');

//assert for getActionsForUser FAILED task
$task4 = new Task($ownerId, $tomorrow);
$task4->changeStatusToActive(Assign::getName(), $ownerId, $agentId);
$task4->changeStatusToFailed(Decline::getName(), $agentId);
assert($task4->getActionsForUser($ownerId) === [], 'owner action(s) for failed task');
assert($task4->getActionsForUser($agentId) === [], 'agent action(s) for failed task');
assert($task4->getActionsForUser($passerbyId) === [], 'passerby action(s) for failed task');

//assert for getActionsForUser EXPIRED task
$task5 = new Task($ownerId, $yesterday);
$task5->changeStatusToExpired();
assert($task5->getActionsForUser($ownerId) === [], 'owner action(s) for expired task');
assert($task5->getActionsForUser($agentId) === [], 'agent action(s) for expired task');
assert($task5->getActionsForUser($passerbyId) === [], 'passerby action(s) for expired task');

//assert for availableActions NEW task
$task6 = new Task($ownerId, $tomorrow);
assert($task6->availableActions($ownerId) === [Assign::getName(), Cancel::getName()], 'owner available action(s) for new task');
assert($task6->availableActions($passerbyId) === [Apply::getName()], 'passerby available action(s) for new task');

//assert for availableActions ACTIVE task
$task6->changeStatusToActive(Assign::getName(), $ownerId, $agentId);
assert($task6->availableActions($ownerId) === [Complete::getName(), Message::getName()], 'owner available action(s) for new task');
assert($task6->availableActions($agentId) === [Decline::getName(), Message::getName()], 'agent available action(s) for new task');
assert($task6->availableActions($passerbyId) === [], 'passerby available action(s) for new task');
