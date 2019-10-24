<?php

namespace Htmlacademy\Actions;

class Decline extends AbstractAction
{
    public static function getName(): string
    {
        return 'отказаться';
    }

    public static function getSlug(): string
    {
        return 'decline';
    }

    public static function isAgent(int $userId, int $agentId): bool
    {
        return $userId === $agentId;
    }
}
