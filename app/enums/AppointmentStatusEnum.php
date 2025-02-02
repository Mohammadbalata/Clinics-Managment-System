<?php

namespace App\Enums;


enum AppointmentStatusEnum: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';
}
