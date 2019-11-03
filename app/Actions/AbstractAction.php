<?php

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
     * @param int $agentId
     *
     * @return bool
     */
    public function isAgent(int $userId, int $agentId): bool
    {
        return ($userId === $agentId && $userId !== null);
    }

    /**
     * @param int $userId
     * @param int $ownerId
     *
     * @return bool
     */
    public function isOwner(int $userId, int $ownerId): bool
    {
        return ($userId === $ownerId && $userId !== null);
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
