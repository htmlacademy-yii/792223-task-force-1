<?php

declare(strict_types=1);

namespace Htmlacademy\Actions;

use Htmlacademy\Models\Task;

class Complete extends AbstractAction
{
    public static function getSlug(): string
    {
        return 'завершить';
    }

    public static function verifyPermission(Task $task, int $userId): bool
    {
        if (!self::isTaskOwner($userId, $task->getOwnerId()) ||
            $task->getStatus() !== $task::STATUS_ACTIVE) {
            return false;
        }

        return true;
    }
}
