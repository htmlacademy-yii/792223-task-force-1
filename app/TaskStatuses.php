<?php

namespace Htmlacademy;

interface TaskStatuses
{
    const STATUS_NEW = 'new';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_FAILED = 'failed';
    const STATUS_ACTIVE = 'in progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_EXPIRED = 'expired';
}

//Мои задания
//Отменённые – все задания со статусом «Отменено» и «Провалено»,
//  где пользователь является заказчиком или исполнителем
//Просроченные – все задания со статусом «Новое»,
//  созданные этим пользователем и для которых истёк срок завершения
