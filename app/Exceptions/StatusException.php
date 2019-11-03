<?php

namespace Htmlacademy\Exceptions;

class StatusException extends TaskForceException
{
    /**
     * @param string $status
     *
     * @return \Htmlacademy\Exceptions\StatusException
     */
    public static function make(string $status): StatusException
    {
        $message = "Task in status {$status} can not be modified in such a way!";
        //TODO: add task ID, status and User ID or json with details to make message more informative
        return new StatusException($message, 409, null);
    }
}
