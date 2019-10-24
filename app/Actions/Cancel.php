<?php

namespace Htmlacademy\Actions;

class Cancel extends AbstractAction
{
    public static function getName(): string
    {
        return 'отменить';
    }

    public static function getSlug(): string
    {
        return 'cancel';
    }

    public static function isAgent(int $userId, int $agentId): bool
    {
        return $userId === $agentId;
    }
}
