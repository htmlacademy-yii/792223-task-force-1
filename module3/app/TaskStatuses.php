<?php

namespace Htmlacademy;

interface TaskStatuses
{
    public const STATUS_NEW = 'new';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_FAILED = 'failed';
    public const STATUS_ACTIVE = 'in progress';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_EXPIRED = 'expired';
}
