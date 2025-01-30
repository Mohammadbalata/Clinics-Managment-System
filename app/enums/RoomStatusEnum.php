<?php

namespace App\Enums;


enum RoomStatusEnum: string
{
    case InUse = 'in_use';
    case Available = 'available';
    case NotActive = 'not_active';
}
