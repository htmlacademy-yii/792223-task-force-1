<?php

namespace Htmlacademy\Actions;

use Htmlacademy\Models\Task;
use Htmlacademy\TaskStatuses;

class Apply extends AbstractAction
{
    public static function getSlug(): string
    {
        return 'откликнуться';
    }

    public static function getName(): string
    {
        return 'apply';
    }

    public static function verifyPermission(Task $task, int $userId): bool
    {
        $userRole = $task->getRoleForUser($userId);
        $taskStatus = $task->getStatus();

        if ($userRole === null &&
            $taskStatus === TaskStatuses::STATUS_NEW) {
            return true;
        }

        return false;
    }
}

