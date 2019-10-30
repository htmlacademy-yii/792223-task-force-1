<?php

namespace Htmlacademy\Models;

use DateTime;
use Exception;
use Htmlacademy\Actions\AbstractAction;
use Htmlacademy\Actions\Apply;
use Htmlacademy\Actions\Assign;
use Htmlacademy\Actions\Cancel;
use Htmlacademy\Actions\Complete;
use Htmlacademy\Actions\Decline;
use Htmlacademy\Actions\Message;
use Htmlacademy\Exceptions\UnauthorizedException;
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
     * @param DateTime $expired_at
     *
     * @throws \Exception
     */
    public function __construct(int $user_id, DateTime $expired_at)
    {
        $this->status = self::STATUS_NEW;
        $this->owner_id = $user_id;
        $this->agent_id = null;
        $this->created_at = new DateTime();
        $this->expired_at = $expired_at;
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
                    return [Cancel::getName(), Assign::getName()];
                }
                if ($this->status === self::STATUS_ACTIVE) {
                    return [Complete::getName()];
                }
                break;

            case self::ROLE_AGENT:
                if ($this->status === self::STATUS_ACTIVE) {
                    return [Decline::getName()];
                }
                break;

            case null:
                if ($this->status === self::STATUS_NEW) {
                    return [Apply::getName()];
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
     * @throws \Exception
     */
    public function getNextStatusForAction(string $actionName): string
    {
        if ($actionName === Assign::getName()) {
            return self::STATUS_ACTIVE;
        }

        if ($actionName === Cancel::getName()) {
            return self::STATUS_CANCELLED;
        }

        if ($actionName === Decline::getName()) {
            return self::STATUS_FAILED;
        }

        if ($actionName === Complete::getName()) {
            return self::STATUS_COMPLETED;
        }

        if ($actionName === Message::getName()) {
            return $this->status;
        }

        if ($actionName === Apply::getName()) {
            return $this->status;
        }

        throw new Exception(self::ACTION_UNKNOWN);
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
            throw new UnauthorizedException();
        }

        if ($actionName !== Assign::getName() || $this->status !== self::STATUS_NEW) {
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

        if ($actionName !== Cancel::getName() || $this->status !== self::STATUS_NEW) {
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

        if ($actionName !== Complete::getName() || $this->status !== self::STATUS_ACTIVE) {
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

        if ($actionName !== Decline::getName() || $this->status !== self::STATUS_ACTIVE) {
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

    /**
     * Get list of all actions
     *
     * @return array
     */
    private function getActionsList()
    {
        return [
            Apply::getName(),
            Assign::getName(),
            Cancel::getName(),
            Complete::getName(),
            Decline::getName(),
            Message::getName(),
        ];
    }

    /**
     * Get list of all statuses
     *
     * @return array
     */
    private function getStatusesList()
    {
        return [
            self::STATUS_NEW,
            self::STATUS_CANCELLED,
            self::STATUS_ACTIVE,
            self::STATUS_FAILED,
            self::STATUS_COMPLETED,
            self::STATUS_EXPIRED,
        ];
    }

    /**
     * @param $userId
     *
     * @return array
     * @throws \Exception
     */
    public function availableActions($userId)
    {
        $actions = $this->getActionsList();
        $availableActions = [];

        foreach ($actions as $action) {
            if (get_parent_class($action) !== AbstractAction::class) {
                $message = "Class {$action} is not a child of AbstractAction";
                throw new Exception($message);
            }

            if ($action::verifyPermission($this, $userId)) {
                array_push($availableActions, $action);
            }
        }

        return $availableActions;
    }

}
