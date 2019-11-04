<?php

declare(strict_types=1);

namespace Htmlacademy\Exceptions;

class RoleException extends TaskForceException
{
    /**
     * @param int $userId
     * @param string $role
     *
     * @return \Htmlacademy\Exceptions\RoleException
     */
    public static function make(int $userId, string $role): RoleException
    {
        $message = "Role for {$userId} can not be {$role}.";
        //TODO: add task ID & details to make message more informative
        return new RoleException($message, 409, null);
    }
}
