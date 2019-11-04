<?php

declare(strict_types=1);

namespace Htmlacademy\Actions;

use Htmlacademy\Models\Task;

abstract class AbstractAction
{
    /**
     * Action name getter
     * @param bool $shortName [optional]
     *
     * @return string
     */
    public static function getName($shortName = false): string
    {
        if ($shortName) {
            $path = explode('\\', get_called_class());
            return array_pop($path);
        }

        return get_called_class();
    }

    /**
     * @param int $userId
     * @param int|null $agentId
     *
     * @return bool
     */
    public static function isTaskAgent(int $userId, ?int $agentId): bool
    {
        return $userId === $agentId;
    }

    /**
     * @param int $userId
     * @param int $ownerId
     *
     * @return bool
     */
    public static function isTaskOwner(int $userId, int $ownerId): bool
    {
        return $userId === $ownerId;
    }

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
