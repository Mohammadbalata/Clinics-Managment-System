<?php

namespace App\Listeners;

use App\Events\AppointmentCancelled;
use App\Models\User;
use App\Notifications\AppointmentCancelledNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAppointmentCancelledNotification
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
    public function handle(AppointmentCancelled $event): void
    {
        $appointment = $event->appointment;
        $user = User::where('id','1')->first();
        $user->notify(new AppointmentCancelledNotification($appointment));
    }
}
