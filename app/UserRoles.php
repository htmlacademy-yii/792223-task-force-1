<?php

namespace Htmlacademy;

interface UserRoles
{
    public const ROLE_OWNER = 'owner';
    public const ROLE_AGENT = 'agent';

    public const ROLE_FORBIDDEN = 'Owner can not be agent!';
}
