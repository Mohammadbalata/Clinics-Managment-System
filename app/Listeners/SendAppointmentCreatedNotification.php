<?php

namespace App\Listeners;

use App\Events\AppointmentCreated;
use App\Notifications\AppointmentCreatedNotification;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendAppointmentCreatedNotification implements ShouldQueue
{
    use InteractsWithQueue;
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

    public function failed(AppointmentCreated $event, \Throwable $exception): void
    {
        Log::error('Failed to send appointment created notification: ' . $exception->getMessage());
    }
        
}
