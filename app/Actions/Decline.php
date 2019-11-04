<?php

declare(strict_types=1);

namespace Htmlacademy\Actions;

use Htmlacademy\Exceptions\ActionException;
use Htmlacademy\Exceptions\StatusException;
use Htmlacademy\Models\Task;

class Decline extends AbstractAction
{
    /** @inheritdoc */
    public static function getSlug(): string
    {
        return 'отказаться';
    }

    /** @inheritdoc */
    public static function handleValidation(Task $task, int $userId): void
    {
        if ($task->getStatus() !== $task::STATUS_ACTIVE) {
            throw StatusException::make($task->getStatus());
        }

        if (!self::isTaskAgent($userId, $task->getAgentId())) {
            throw ActionException::make();
        }
    }
}
