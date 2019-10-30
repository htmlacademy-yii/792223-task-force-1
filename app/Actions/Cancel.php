<?php

namespace Htmlacademy\Actions;

use Htmlacademy\Models\Task;
use Htmlacademy\TaskStatuses;
use Htmlacademy\UserRoles;

class Cancel extends AbstractAction
{
    public static function getSlug(): string
    {
        return 'отменить';
    }

    public static function getName($shortName = false): string
    {
        if ($shortName) {
            $path = explode('\\', Cancel::class);
            return array_pop($path);
        }

        return Cancel::class;
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
