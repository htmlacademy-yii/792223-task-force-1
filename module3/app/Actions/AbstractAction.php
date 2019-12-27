<?php

declare(strict_types=1);

namespace Htmlacademy\Actions;

use Htmlacademy\Exceptions\TaskForceException;
use Htmlacademy\Models\Task;

abstract class AbstractAction
{
    /**
     * Action name getter
     *
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
     * @param \Htmlacademy\Models\Task $task
     * @param int $userId
     *
     * @return bool
     */
    public static function verifyPermission(Task $task, int $userId): bool
    {
        $action = self::getName();
        try {
            $action::handleValidation($task, $userId);
        } catch (TaskForceException $e) {
            return false;
        }

        return true;
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
     * @throws \Htmlacademy\Exceptions\ActionException
     * @throws \Htmlacademy\Exceptions\RoleException
     * @throws \Htmlacademy\Exceptions\StatusException
     */
    abstract public static function handleValidation(Task $task, int $userId): void;
}
