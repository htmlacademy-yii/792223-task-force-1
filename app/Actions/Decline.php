<?php

declare(strict_types=1);

namespace Htmlacademy\Actions;

use Htmlacademy\Models\Task;

class Decline extends AbstractAction
{
    public static function getSlug(): string
    {
        return 'отказаться';
    }

    public static function verifyPermission(Task $task, int $userId): bool
    {
        if (!self::isTaskAgent($userId, $task->getAgentId()) ||
            $task->getStatus() !== $task::STATUS_ACTIVE) {
            return false;
        }

        return true;
    }
}
