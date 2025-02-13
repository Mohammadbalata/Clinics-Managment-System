<?php

namespace App\Listeners;

use App\Events\AppointmentConfirmed;
use App\Notifications\AppointmentConfirmedNotification;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendAppointmentConfirmedNotification implements ShouldQueue
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
    public function handle(AppointmentConfirmed $event): void
    {

        $appointment = $event->appointment;
        $this->notificationService->sendNotificationToAdmins(new  AppointmentConfirmedNotification($appointment));
        return;
    }
    public function failed(AppointmentConfirmed $event, \Throwable $exception): void
    {
        Log::error('Failed to send appointment confirmed notification: ' . $exception->getMessage());
    }
}
