<?php

namespace App\Listeners;

use App\Events\AppointmentConfirmed;
use App\Models\User;
use App\Notifications\AppointmentConfirmedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAppointmentConfirmedNotification
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
    public function handle(AppointmentConfirmed $event): void
    {
        $appointment = $event->appointment;
        $user = User::where('id','1')->first();
        $user->notify(new AppointmentConfirmedNotification($appointment));
    }
}
