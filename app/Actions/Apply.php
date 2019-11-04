<?php

declare(strict_types=1);

namespace Htmlacademy\Actions;

use Htmlacademy\Exceptions\ActionException;
use Htmlacademy\Exceptions\StatusException;
use Htmlacademy\Models\Task;

class Apply extends AbstractAction
{
    /** @inheritdoc */
    public static function getSlug(): string
    {
        return 'откликнуться';
    }

    /** @inheritdoc */
    public static function handleValidation(Task $task, int $userId): void
    {
        if ($task->getStatus() !== $task::STATUS_NEW) {
            throw StatusException::make($task->getStatus());
        }

        //TODO: any user that has role Agent can apply
        // NEW task do not have agents assigned
        if (self::isTaskOwner($userId, $task->getOwnerId())) {
            throw ActionException::make();
        }
    }
}
