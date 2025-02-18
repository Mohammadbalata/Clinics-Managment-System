<?php

namespace App\Listeners;

use App\Enums\AppointmentStatusEnum;
use App\Enums\RoomStatusEnum;
use App\Events\AppointmentCreated;
use App\Models\Appointment;
use App\Models\Room;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateRoomStatus
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AppointmentCreated $event): void
    {
        $appointment = $event->appointment;
        $room = Room::findOrfail($appointment->room_id);
        
        if ($room) {
            $appointmentsCount = Appointment::where('room_id', $appointment->room_id)
            ->where('date', $appointment->date)
            ->where('status', '!=', AppointmentStatusEnum::Cancelled)
            ->count();

            if ($appointmentsCount >= $room->capacity) {
                $room->status = RoomStatusEnum::InUse; 
                $room->save();
            }
        }
    }
}
