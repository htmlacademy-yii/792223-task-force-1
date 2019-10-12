<?php

namespace Htmlacademy;

interface TaskActions
{
    public const ACTION_APPLY = 'apply';
    public const ACTION_ASSIGN = 'assign';
    public const ACTION_CANCEL = 'cancel';
    public const ACTION_DECLINE = 'decline';
    public const ACTION_COMPLETE = 'complete';
    public const ACTION_MESSAGE = 'message';

    public const ACTION_NOT_ALLOWED = 'Current status does not allow such action!';
    public const ACTION_UNAUTHORIZED = 'Action unauthorized!';
    public const ACTION_ASSIGNED = 'Task is already assigned to agent!';
    public const ACTION_NO_AVAILABLE = 'No available actions!';
}
