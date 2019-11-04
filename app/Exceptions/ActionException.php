<?php

declare(strict_types=1);

namespace Htmlacademy\Exceptions;

class ActionException extends TaskForceException
{
    /**
     * @return \Htmlacademy\Exceptions\ActionException
     */
    public static function make(): ActionException
    {
        $message = "Action is unauthorized!";
        //TODO: add task ID, status and User ID or json with details to make message more informative
        return new ActionException($message, 403, null);
    }
}
