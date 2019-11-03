<?php

namespace Htmlacademy\Actions;

use Htmlacademy\Models\Task;
use Htmlacademy\TaskStatuses;
use Htmlacademy\UserRoles;

class Decline extends AbstractAction
{
    public static function getSlug(): string
    {
        return 'отказаться';
    }

    public static function verifyPermission(Task $task, int $userId): bool
    {
        $userRole = $task->getRoleForUser($userId);
        $taskStatus = $task->getStatus();

        if ($userRole !== UserRoles::ROLE_AGENT ||
            $taskStatus !== TaskStatuses::STATUS_ACTIVE) {
            return false;
        }

        return true;
    }
}
