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
     * @throws \Htmlacademy\Exceptions\RoleException
     */
    private function setAgentId(int $userId): void
    {
        if ($this->owner_id === $userId) {
            throw RoleException::make($userId, self::ROLE_AGENT);
        }
        $this->agent_id = $userId;
    }

    /** @return int|null */
    public function getAgentId(): ?int
    {
        return $this->agent_id;
    }

    /** @return int */
    public function getOwnerId(): int
    {
        return $this->owner_id;
    }

    /** @return string */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param \Htmlacademy\Actions\AbstractAction $action
     *
     * @return string
     * @throws \Htmlacademy\Exceptions\ActionException
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
     * @param int $userId
     * @param int $agentId
     *
     * @throws \Htmlacademy\Exceptions\ActionException
     * @throws \Htmlacademy\Exceptions\RoleException
     * @throws \Htmlacademy\Exceptions\StatusException
     */
    public function performAssign(int $userId, int $agentId): void
    {
        Assign::handleValidation($this, $userId);
        $this->setAgentId($agentId);
        $this->status = self::STATUS_ACTIVE;
    }

    /**
     * @param int $userId
     *
     * @throws \Htmlacademy\Exceptions\ActionException
     * @throws \Htmlacademy\Exceptions\RoleException
     * @throws \Htmlacademy\Exceptions\StatusException
     */
    public function performCancel(int $userId): void
    {
        Cancel::handleValidation($this, $userId);
        $this->status = self::STATUS_CANCELLED;
    }

    /**
     * @param int $userId
     *
     * @throws \Htmlacademy\Exceptions\ActionException
     * @throws \Htmlacademy\Exceptions\RoleException
     * @throws \Htmlacademy\Exceptions\StatusException
     */
    public function performComplete(int $userId): void
    {
        Complete::handleValidation($this, $userId);
        $this->status = self::STATUS_COMPLETED;
    }

    /**
     * @param int $userId
     *
     * @throws \Htmlacademy\Exceptions\ActionException
     * @throws \Htmlacademy\Exceptions\RoleException
     * @throws \Htmlacademy\Exceptions\StatusException
     */
    public function performDecline(int $userId): void
    {
        Decline::handleValidation($this, $userId);
        $this->status = self::STATUS_FAILED;
    }

    /**
     * @throws \Exception
     */
    public function performExpire(): void
    {
        if ($this->status === self::STATUS_NEW && new DateTime() > $this->expired_at) {
            $this->status = self::STATUS_EXPIRED;
        }
    }

    /**
     * @param int $userId
     *
     * @throws \Htmlacademy\Exceptions\ActionException
     * @throws \Htmlacademy\Exceptions\RoleException
     * @throws \Htmlacademy\Exceptions\StatusException
     */
    public function performApply(int $userId): void
    {
        Apply::handleValidation($this, $userId);
    }

    /**
     * @param int $userId
     *
     * @throws \Htmlacademy\Exceptions\ActionException
     * @throws \Htmlacademy\Exceptions\RoleException
     * @throws \Htmlacademy\Exceptions\StatusException
     */
    public function performMessage(int $userId): void
    {
        Message::handleValidation($this, $userId);
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
