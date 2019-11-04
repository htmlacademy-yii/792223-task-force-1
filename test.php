<?php

declare(strict_types=1);

namespace Htmlacademy;

require_once 'vendor/autoload.php';

use DateTime;
use Htmlacademy\Actions\Apply;
use Htmlacademy\Actions\Assign;
use Htmlacademy\Actions\Cancel;
use Htmlacademy\Actions\Complete;
use Htmlacademy\Actions\Decline;
use Htmlacademy\Actions\Message;
use Htmlacademy\Models\Task;

ini_set('display_errors', 'On');
error_reporting(E_ALL);

$ownerId = 1;
$agentId = 2;
$passerbyId = 42;
$tomorrow = new DateTime('tomorrow');
$yesterday = new DateTime('yesterday');

//assert for getNextStatusForAction
$task = new Task($ownerId, $tomorrow);
assert($task->getNextStatusForAction(new Apply) === TaskStatuses::STATUS_NEW, 'apply action');
assert($task->getNextStatusForAction(new Message) === $task->getStatus(), 'message action');
assert($task->getNextStatusForAction(new Assign) === TaskStatuses::STATUS_ACTIVE, 'assign action');
assert($task->getNextStatusForAction(new Cancel) === TaskStatuses::STATUS_CANCELLED, 'cancel action');
assert($task->getNextStatusForAction(new Decline) === TaskStatuses::STATUS_FAILED, 'decline action');
assert($task->getNextStatusForAction(new Complete) === TaskStatuses::STATUS_COMPLETED,
    'complete action');

//assert for getActionsForUser NEW task
$task2 = new Task($ownerId, $tomorrow);
assert($task2->getActionsForUser($ownerId) === [Assign::getName(), Cancel::getName()],
    'owner action(s) for new task');
assert($task2->getActionsForUser($passerbyId) === [Apply::getName()], 'passerby action(s) for new task');

//assert for getActionsForUser ACTIVE task
$task2->performAssign($ownerId, $agentId);
//var_dump($task2->getActionsForUser($ownerId));
//var_dump($task2->getActionsForUser($agentId));
assert($task2->getActionsForUser($ownerId) === [Complete::getName(), Message::getName()], 'owner action(s) for active task');
assert($task2->getActionsForUser($agentId) === [Decline::getName(), Message::getName()], 'agent action(s) for active task');
assert($task2->getActionsForUser($passerbyId) === [], 'passerby action(s) for active task');

//assert for getActionsForUser COMPLETED task
$task2->performComplete($ownerId);
assert($task2->getActionsForUser($ownerId) === [], 'owner action(s) for completed task');
assert($task2->getActionsForUser($agentId) === [], 'agent action(s) for completed task');
assert($task2->getActionsForUser($passerbyId) === [], 'passerby action(s) for completed task');

//assert for getActionsForUser CANCELLED task
$task3 = new Task($ownerId, $tomorrow);
$task3->performCancel($ownerId);
assert($task3->getActionsForUser($ownerId) === [], 'owner action(s) for cancelled task');
assert($task3->getActionsForUser($agentId) === [], 'agent action(s) for cancelled task');
assert($task3->getActionsForUser($passerbyId) === [], 'passerby action(s) for cancelled task');

//assert for getActionsForUser FAILED task
$task4 = new Task($ownerId, $tomorrow);
$task4->performAssign($ownerId, $agentId);
$task4->performDecline($agentId);
assert($task4->getActionsForUser($ownerId) === [], 'owner action(s) for failed task');
assert($task4->getActionsForUser($agentId) === [], 'agent action(s) for failed task');
assert($task4->getActionsForUser($passerbyId) === [], 'passerby action(s) for failed task');

//assert for getActionsForUser EXPIRED task
$task5 = new Task($ownerId, $yesterday);
$task5->performExpire();
assert($task5->getActionsForUser($ownerId) === [], 'owner action(s) for expired task');
assert($task5->getActionsForUser($agentId) === [], 'agent action(s) for expired task');
assert($task5->getActionsForUser($passerbyId) === [], 'passerby action(s) for expired task');
