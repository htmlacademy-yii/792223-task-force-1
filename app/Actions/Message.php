<?php

namespace Htmlacademy\Actions;

class Message extends AbstractAction
{
    public static function getName(): string
    {
        return 'написать сообщение';
    }

    public static function getSlug(): string
    {
        return 'message';
    }

    public static function isAgent(int $userId, int $agentId): bool
    {
        return $userId === $agentId;
    }
}
