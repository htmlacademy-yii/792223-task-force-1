<?php

namespace Htmlacademy\Model;

use Htmlacademy\TaskActions;
use Htmlacademy\TaskStatuses;
use Htmlacademy\UserRoles;

class Task implements TaskActions, TaskStatuses, UserRoles
{
    //private $id;
    private $status;
    private $owner_id;
    private $agent_id;
    private $created_at;
    private $expired_at;
    private $updated_at;
    //private $name;
    //private $description;
    //private $budget;
    //private $category_id;
    //private $location_id;
    //private $chat_id;
    //private $review_id;
    //private $has_attachments;

    public function __construct($user_id, $expired_at)
    {
        $this->status = self::STATUS_NEW;
        $this->owner_id = $user_id;
        $this->agent_id = null;
        $this->created_at = date('Y-m-d H:i:s');
        $this->expired_at = $expired_at;
        $this->updated_at = $this->created_at;
    }

    /**
     * @param int $userId
     *
     * @return string|null
     */
    public function getRoleForUser(int $userId)
    {
        if ($userId === $this->owner_id) {
            return self::ROLE_OWNER;
        }
        if ($userId === $this->agent_id) {
            return self::ROLE_AGENT;
        }

        return null;
        //throw Exception
        //though role is optional
    }

    /**
     * @param int $userId
     *
     * @return array
     */
    public function getActionsForUser(int $userId): array
    {
        if (date('Y-m-d H:i:s') > $this->expired_at) {
            return [];
            //throw Exception
        }

        $userRole = $this->getRoleForUser($userId);

        switch ($userRole) {
            case self::ROLE_OWNER:
                if ($this->status === self::STATUS_NEW) {
                    return [self::ACTION_CANCEL, self::ACTION_ASSIGN];
                }
                if ($this->status === self::STATUS_ACTIVE) {
                    return [self::ACTION_COMPLETE];
                }
                break;

            case self::ROLE_AGENT:
                if ($this->status === self::STATUS_ACTIVE) {
                    return [self::ACTION_DECLINE];
                }
                break;

            case null:
                if ($this->status === self::STATUS_NEW) {
                    return [self::ACTION_APPLY];
                }
                break;
        }

        return [];
        //throw Exception
    }

    /**
     * @param string $actionName
     *
     * @return string
     */
    public function getStatusForAction(string $actionName): string
    {
        if ($actionName === self::ACTION_ASSIGN) {
            return self::STATUS_ACTIVE;
        }

        if ($actionName === self::ACTION_CANCEL) {
            return self::STATUS_CANCELLED;
        }

        if ($actionName === self::ACTION_DECLINE) {
            return self::STATUS_FAILED;
        }

        if ($actionName === self::ACTION_COMPLETE) {
            return self::STATUS_COMPLETED;
        }

        return $this->status;
    }

    /*
     * это не совсем поняла:
     * "должны быть методы, которые возвращают
     * список из всех доступных действий и статусов;"
     * просто вернуть массив всех действий и всех статусов?
    */

}
