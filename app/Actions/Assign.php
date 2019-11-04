<?php

declare(strict_types=1);

namespace Htmlacademy\Actions;

use Htmlacademy\Exceptions\ActionException;
use Htmlacademy\Exceptions\RoleException;
use Htmlacademy\Exceptions\StatusException;
use Htmlacademy\Models\Task;

class Assign extends AbstractAction
{
    /** @inheritdoc */
    public static function getSlug(): string
    {
        return 'принять';
    }

    /** @inheritdoc */
    public static function handleValidation(Task $task, int $userId): void
    {
        if ($task->getStatus() !== $task::STATUS_NEW) {
            throw StatusException::make($task->getStatus());
        }

        if (!self::isTaskOwner($userId, $task->getOwnerId())) {
            throw ActionException::make();
        }

        if ($task->getAgentId() !== null) {
            throw RoleException::make($userId, $task::ROLE_AGENT);
        }
    }
}
