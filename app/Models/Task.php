<?php

namespace Htmlacademy\Models;

use DateTime;
use Exception;
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

    /**
     * Task constructor.
     *
     * @param int $user_id
     * @param string $expired_at
     *
     * @throws \Exception
     */
    public function __construct(int $user_id, string $expired_at)
    {
        $this->status = self::STATUS_NEW;
        $this->owner_id = $user_id;
        $this->agent_id = null;
        $this->created_at = new DateTime();
        $this->expired_at = new DateTime($expired_at);
        $this->updated_at = $this->created_at;
    }

    /**
     * @param int $userId
     *
     * @throws \Exception
     */
    private function setAgentId(int $userId)
    {
        if ($this->owner_id === $userId) {
            throw new Exception(self::ROLE_FORBIDDEN);
        }
        $this->agent_id = $userId;
    }

    public function getStatus()
    {
        return $this->status;
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
        //throw Exception?
        //role is optional
    }

    /**
     * @param int $userId
     *
     * @return array
     * @throws \Exception
     */
    public function getActionsForUser(int $userId): array
    {
        if (new DateTime() > $this->expired_at) {
            return [];
            //throw new Exception(self::STATUS_EXCEPTION_EXPIRED);
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
        //throw new Exception(self::ACTION_NO_AVAILABLE);
    }

    /**
     * @param string $actionName
     *
     * @return string
     */
    public function getNextStatusForAction(string $actionName): string
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

    /**
     * @param string $actionName
     * @param int $userId
     * @param int $agentId
     *
     * @throws \Exception
     */
    public function changeStatusToActive(string $actionName, int $userId, int $agentId)
    {
        $userRole = $this->getRoleForUser($userId);

        if ($userRole !== self::ROLE_OWNER) {
            throw new Exception(self::ACTION_UNAUTHORIZED);
        }

        if ($actionName !== self::ACTION_ASSIGN || $this->status !== self::STATUS_NEW) {
            throw new Exception(self::ACTION_NOT_ALLOWED);
        }

        // ↓ ↓ ↓ YAGNI or never too much?
        if ($this->agent_id !== null) {
            throw new Exception(self::ACTION_ASSIGNED);
        }

        $this->setAgentId($agentId);
        $this->status = self::STATUS_ACTIVE;
    }

    /**
     * @param string $actionName
     * @param int $userId
     *
     * @throws \Exception
     */
    public function changeStatusToCancelled(string $actionName, int $userId)
    {
        $userRole = $this->getRoleForUser($userId);

        if ($userRole !== self::ROLE_OWNER) {
            throw new Exception(self::ACTION_UNAUTHORIZED);
        }

        if ($actionName !== self::ACTION_CANCEL || $this->status !== self::STATUS_NEW) {
            throw new Exception(self::ACTION_NOT_ALLOWED);
        }

        $this->status = self::STATUS_CANCELLED;
    }

    /**
     * @param string $actionName
     * @param int $userId
     *
     * @throws \Exception
     */
    public function changeStatusToCompleted(string $actionName, int $userId)
    {
        $userRole = $this->getRoleForUser($userId);

        if ($userRole !== self::ROLE_OWNER) {
            throw new Exception(self::ACTION_UNAUTHORIZED);
        }

        if ($actionName !== self::ACTION_COMPLETE || $this->status !== self::STATUS_ACTIVE) {
            throw new Exception(self::ACTION_NOT_ALLOWED);
        }

        $this->status = self::STATUS_COMPLETED;
    }

    /**
     * @param string $actionName
     * @param int $userId
     *
     * @throws \Exception
     */
    public function changeStatusToFailed(string $actionName, int $userId)
    {
        $userRole = $this->getRoleForUser($userId);

        if ($userRole !== self::ROLE_AGENT) {
            throw new Exception(self::ACTION_UNAUTHORIZED);
        }

        if ($actionName !== self::ACTION_DECLINE || $this->status !== self::STATUS_ACTIVE) {
            throw new Exception(self::ACTION_NOT_ALLOWED);
        }

        $this->status = self::STATUS_FAILED;
    }

    /**
     * @throws \Exception
     */
    public function changeStatusToExpired()
    {
        if ($this->status === self::STATUS_NEW && new DateTime() > $this->expired_at) {
            $this->status = self::STATUS_EXPIRED;
        }
    }

}
