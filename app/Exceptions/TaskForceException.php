<?php

namespace Htmlacademy\Exceptions;

use Exception;
use Throwable;

/**
 * Construct the TaskForceException.
 * @param string $message [optional] The Exception message to throw.
 * @param int $code [optional] The Exception code.
 * @param Throwable $previous [optional] The previous throwable used for the exception chaining.
 * @package Htmlacademy\Exceptions
 */
class TaskForceException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}