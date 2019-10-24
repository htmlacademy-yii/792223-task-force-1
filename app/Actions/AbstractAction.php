<?php

namespace Htmlacademy\Actions;

abstract class AbstractAction
{
    /**
     * Action name getter
     *
     * @return string
     */
    abstract public static function getName(): string;

    /**
     * Action slug getter
     *
     * @return string
     */
    abstract public static function getSlug(): string;

    /**
     * @param $userId
     * @param $agentId
     *
     * @return bool
     */
    abstract public static function isAgent(int $userId, int $agentId): bool;
}
