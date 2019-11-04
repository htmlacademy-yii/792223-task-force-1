<?php

declare(strict_types=1);

namespace Htmlacademy\Models;

use DateTime;
use Htmlacademy\Actions\AbstractAction;
use Htmlacademy\Actions\Apply;
use Htmlacademy\Actions\Assign;
use Htmlacademy\Actions\Cancel;
use Htmlacademy\Actions\Complete;
use Htmlacademy\Actions\Decline;
use Htmlacademy\Actions\Message;
use Htmlacademy\Exceptions\ActionException;
use Htmlacademy\Exceptions\RoleException;
use Htmlacademy\Exceptions\StatusException;
use Htmlacademy\TaskStatuses;
use Htmlacademy\UserRoles;

class Task implements TaskStatuses, UserRoles
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
    private function setAgentId(int $userId): void
    {
        if ($this->owner_id === $userId) {
            throw RoleException::make($userId, UserRoles::ROLE_AGENT);
        }
        $this->agent_id = $userId;
    }

    public function getAgentId(): ?int
    {
        return $this->agent_id;
    }

    public function getOwnerId(): int
    {
        return $this->owner_id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param AbstractAction $action
     *
     * @return string
     * @throws \Exception
     */
    public function getNextStatusForAction(AbstractAction $action): string
    {
        if ($action::getName() === Assign::getName()) {
            return self::STATUS_ACTIVE;
        }

        if ($action::getName() === Cancel::getName()) {
            return self::STATUS_CANCELLED;
        }

        if ($action::getName() === Decline::getName()) {
            return self::STATUS_FAILED;
        }

        if ($action::getName() === Complete::getName()) {
            return self::STATUS_COMPLETED;
        }

        if ($action::getName() === Message::getName()) {
            return $this->status;
        }

        if ($action::getName() === Apply::getName()) {
            return $this->status;
        }

        throw ActionException::make();
    }

    /**
     * @param AbstractAction $action
     * @param int $userId
     * @param int $agentId
     *
     * @throws \Exception
     */
    public function changeStatusToActive(AbstractAction $action, int $userId, int $agentId): void
    {
        if (!$action->isTaskOwner($userId, $this->owner_id)) {
            throw ActionException::make();
        }

        if ($action::getName() !== Assign::getName() || $this->status !== self::STATUS_NEW) {
            throw StatusException::make($this->status);
        }

        if ($this->agent_id !== null) {
            throw RoleException::make($userId, UserRoles::ROLE_AGENT);
        }

        $this->setAgentId($agentId);
        $this->status = self::STATUS_ACTIVE;
    }

    /**
     * @param AbstractAction $action
     * @param int $userId
     *
     * @throws \Exception
     */
    public function changeStatusToCancelled(AbstractAction $action, int $userId): void
    {
        if (!$action->isTaskOwner($userId, $this->owner_id)) {
            throw ActionException::make();
        }

        if ($action::getName() !== Cancel::getName() || $this->status !== self::STATUS_NEW) {
            throw StatusException::make($this->status);
        }

        $this->status = self::STATUS_CANCELLED;
    }

    /**
     * @param AbstractAction $action
     * @param int $userId
     *
     * @throws \Exception
     */
    public function changeStatusToCompleted(AbstractAction $action, int $userId): void
    {
        if (!$action->isTaskOwner($userId, $this->owner_id)) {
            throw ActionException::make();
        }

        if ($action::getName() !== Complete::getName() || $this->status !== self::STATUS_ACTIVE) {
            throw StatusException::make($this->status);
        }

        $this->status = self::STATUS_COMPLETED;
    }

    /**
     * @param AbstractAction $action
     * @param int $userId
     *
     * @throws \Exception
     */
    public function changeStatusToFailed(AbstractAction $action, int $userId): void
    {
        if (!$action->isTaskAgent($userId, $this->agent_id)) {
            throw ActionException::make();
        }

        if ($action::getName() !== Decline::getName() || $this->status !== self::STATUS_ACTIVE) {
            throw StatusException::make($this->status);
        }

        $this->status = self::STATUS_FAILED;
    }

    /**
     * @throws \Exception
     */
    public function changeStatusToExpired(): void
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
    private function getActionsList(): array
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
    private function getStatusesList(): array
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
    public function getActionsForUser($userId): array
    {
        $actions = $this->getActionsList();
        $availableActions = [];

        foreach ($actions as $action) {
            if ($action::verifyPermission($this, $userId)) {
                array_push($availableActions, $action);
            }
        }

        return $availableActions;
    }

}
