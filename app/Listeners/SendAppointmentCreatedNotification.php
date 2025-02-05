<?php

namespace App\Listeners;

use App\Events\AppointmentCreated;
use App\Models\User;
use App\Notifications\AppointmentCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAppointmentCreatedNotification
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
        $user = User::where('id','1')->first();
        $user->notify(new AppointmentCreatedNotification($appointment));
    }
}
