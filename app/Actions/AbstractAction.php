<?php

namespace Htmlacademy\Actions;

use Htmlacademy\Models\Task;

abstract class AbstractAction
{
    /**
     * Action name getter
     *
     * @return string
     */
    abstract public static function getName(): string;

    /**
     * Action slug getter
     *
     * @return string
     */
    abstract public static function getSlug(): string;

    /**
     * @param \Htmlacademy\Models\Task $task
     * @param int $userId
     *
     * @return bool
     */
    abstract public static function verifyPermission(Task $task, int $userId): bool;
}
