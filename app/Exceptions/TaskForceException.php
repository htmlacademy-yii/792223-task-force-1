<?php

namespace Htmlacademy\Exceptions;

use Exception;
use Throwable;

/**
 * Class TaskForceException
 *
 * @package Htmlacademy\Exceptions
 */
class TaskForceException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
