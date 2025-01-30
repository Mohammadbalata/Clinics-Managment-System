<?php

namespace App\Enums;


enum RoomTypeEnum: string
{
    case Consultation = 'consultation';
    case Surgery = 'surgery';
    case Procedure = 'procedure';
}
