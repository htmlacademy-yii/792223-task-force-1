<?php

namespace Htmlacademy\Actions;

class Apply extends AbstractAction
{
    public static function getName(): string
    {
        return 'откликнуться';
    }

    public static function getSlug(): string
    {
        return 'apply';
    }

    public static function isAgent(int $userId, int $agentId): bool
    {
        return $userId === $agentId;
    }
}
