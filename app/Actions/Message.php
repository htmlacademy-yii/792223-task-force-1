<?php

declare(strict_types=1);

namespace Htmlacademy\Actions;

use Htmlacademy\Models\Task;

class Message extends AbstractAction
{
    public static function getSlug(): string
    {
        return 'написать сообщение';
    }

    public static function verifyPermission(Task $task, int $userId): bool
    {
        if ((self::isTaskOwner($userId, $task->getOwnerId()) || self::isTaskAgent($userId, $task->getAgentId())) &&
            $task->getStatus() === $task::STATUS_ACTIVE) {
            return true;
        }

        return false;
    }
}
