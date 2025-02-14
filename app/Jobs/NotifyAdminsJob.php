<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class NotifyAdminsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $appointment;
    protected $notificationClass;

    /**
     * Create a new job instance.
     */
    public function __construct(Appointment $appointment, string $notificationClass)
    {
        $this->appointment = $appointment;
        $this->notificationClass = $notificationClass;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $admins = User::where('notifications_enabled', true)->get();
        foreach ($admins as $admin) {
            $admin->notify(new $this->notificationClass($this->appointment));
        }
    }
}
