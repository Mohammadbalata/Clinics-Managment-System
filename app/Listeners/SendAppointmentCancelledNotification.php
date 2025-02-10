<?php

namespace App\Listeners;

use App\Events\AppointmentCancelled;
use App\Notifications\AppointmentCancelledNotification;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAppointmentCancelledNotification
{
    protected $notificationService;
    /**
     * Create the event listener.
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the event.
     */
    public function handle(AppointmentCancelled $event): void
    {

        $appointment = $event->appointment;
        $this->notificationService->sendNotificationToAdmins(new  AppointmentCancelledNotification($appointment));
        return ;
       
    }
}
