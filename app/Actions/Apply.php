<?php

declare(strict_types=1);

namespace Htmlacademy\Actions;

use Htmlacademy\Models\Task;

class Apply extends AbstractAction
{
    public static function getSlug(): string
    {
        return 'откликнуться';
    }

    public static function verifyPermission(Task $task, int $userId): bool
    {
        //TODO: any user that has role Agent can apply
        // NEW task do not have agents assigned
        if (!self::isTaskOwner($userId, $task->getOwnerId()) &&
            $task->getStatus() === $task::STATUS_NEW) {
            return true;
        }

        return false;
    }
}
