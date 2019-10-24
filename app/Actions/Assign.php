<?php

namespace Htmlacademy\Actions;

class Assign extends AbstractAction
{
    public static function getName(): string
    {
        return 'принять';
    }

    public static function getSlug(): string
    {
        return 'assign';
    }

    public static function isAgent(int $userId, int $agentId): bool
    {
        return $userId === $agentId;
    }
}
