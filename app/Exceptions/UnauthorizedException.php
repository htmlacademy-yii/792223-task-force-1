<?php

namespace Htmlacademy\Exceptions;

use Throwable;

class UnauthorizedException extends TaskForceException
{
    /**
     * UnauthorizedException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = 'Action is unauthorized!', int $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
