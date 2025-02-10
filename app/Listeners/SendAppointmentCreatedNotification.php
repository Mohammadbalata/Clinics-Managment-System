<?php

namespace App\Listeners;

use App\Events\AppointmentCreated;
use App\Notifications\AppointmentCreatedNotification;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAppointmentCreatedNotification
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
    public function handle(AppointmentCreated $event): void
    {
        $appointment = $event->appointment;
        $this->notificationService->sendNotificationToAdmins(new  AppointmentCreatedNotification($appointment));
        return ;
    }
        
}
