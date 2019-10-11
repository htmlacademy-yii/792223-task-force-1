<?php

namespace Htmlacademy\Model;

/*
должны быть методы, которые возвращают
список из всех доступных действий и статусов;
*/

class Task
{
    public const ROLE_OWNER = 'заказчик';
    public const ROLE_AGENT = 'исполнитель';

    public const ACTION_APPLY = 'откликнуться';
    public const ACTION_ACCEPT = 'принять';
    public const ACTION_CANCEL = 'отменить/отказаться';
    public const ACTION_COMPLETE = 'завершить';

    public const STATUS_NEW = 'новое';
    public const STATUS_CANCELLED = 'отменено/провалено';
    public const STATUS_EXPIRED_FAILED = 'просрочено';
    public const STATUS_ACTIVE = 'на исполнении';
    public const STATUS_COMPLETED = 'завершено';

    //private $id;
    private $status;
    private $owner_id;
    private $agent_id;
    private $created_at;
    private $expires_at;
    private $updated_at;

    public function __construct($user_id, $expires_at)
    {
        $this->status = self::STATUS_NEW;
        $this->owner_id = $user_id;
        $this->agent_id = null;
        $this->created_at = date('Y-m-d H:i:s');
        $this->expires_at = $expires_at;
        $this->updated_at = $this->created_at;
    }

    public function getRoleForUser($userId)
    {
        if ($userId === $this->owner_id) {
            return self::ROLE_OWNER;
        }
        if ($userId === $this->agent_id) {
            return self::ROLE_AGENT;
        }

        return null;
    }

    public function getActionsForUser($userId)
    {
        //список доступных действий, который зависит от:
        //текущего статуса задания.
        //id пользователя (то есть роли).

        if (date('Y-m-d H:i:s') > $this->expires_at) {
            return null;
        }

        $userRole = $this->getRoleForUser($userId);

        switch ($userRole) {
            case self::ROLE_OWNER:
                if ($this->status === self::STATUS_NEW) {
                    return [self::ACTION_CANCEL, self::ACTION_ACCEPT];
                }
                if ($this->status === self::STATUS_ACTIVE) {
                    return [self::ACTION_COMPLETE];
                }
                break;

            case self::ROLE_AGENT:
                if ($this->status === self::STATUS_ACTIVE) {
                    return [self::ACTION_CANCEL];
                }
                break;

            case null:
                if ($this->status === self::STATUS_NEW) {
                    return [self::ACTION_APPLY];
                }
                break;
        }

        return null;
    }

    public function getStatusForAction($actionName)
    {
        //метод для возврата имени статуса,
        //в который перейдёт задание после конкретного действия.

        $statusName = $this->status;

        if ($actionName === self::ACTION_ACCEPT) {
            return self::STATUS_ACTIVE;
        }

        if ($actionName === self::ACTION_CANCEL) {
            return self::STATUS_CANCELLED;
        }

        if ($actionName === self::ACTION_COMPLETE) {
            return self::STATUS_COMPLETED;
        }

        return $statusName;
    }

}
