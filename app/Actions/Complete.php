<?php

namespace Htmlacademy\Actions;

use Htmlacademy\Models\Task;
use Htmlacademy\TaskStatuses;
use Htmlacademy\UserRoles;

class Complete extends AbstractAction
{
    public static function getSlug(): string
    {
        return 'завершить';
    }

    public static function getName($shortName = false): string
    {
        if ($shortName) {
            $path = explode('\\', Complete::class);
            return array_pop($path);
        }

        return Complete::class;
    }

    public static function verifyPermission(Task $task, int $userId): bool
    {
        $userRole = $task->getRoleForUser($userId);
        $taskStatus = $task->getStatus();

        if ($userRole !== UserRoles::ROLE_OWNER ||
            $taskStatus !== TaskStatuses::STATUS_ACTIVE) {
            return false;
        }

        return true;
    }
}
