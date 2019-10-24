<?php

namespace Htmlacademy\Actions;

class Complete extends AbstractAction
{
    public static function getName(): string
    {
        return 'завершить';
    }

    public static function getSlug(): string
    {
        return 'complete';
    }

    public static function isAgent(int $userId, int $agentId): bool
    {
        return $userId === $agentId;
    }
}
