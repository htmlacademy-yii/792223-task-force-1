<?php

namespace Htmlacademy\Actions;

use Htmlacademy\Models\Task;
use Htmlacademy\TaskStatuses;
use Htmlacademy\UserRoles;

class Assign extends AbstractAction
{
    public static function getSlug(): string
    {
        return 'принять';
    }

    public static function getName(): string
    {
        return 'assign';
    }

    public static function verifyPermission(Task $task, int $userId): bool
    {
        $userRole = $task->getRoleForUser($userId);
        $taskStatus = $task->getStatus();

        if ($userRole !== UserRoles::ROLE_OWNER ||
            $taskStatus !== TaskStatuses::STATUS_NEW) {
            return false;
        }

        return true;
    }
}
