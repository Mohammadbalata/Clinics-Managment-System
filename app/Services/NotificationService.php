<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Notifications\Notification;

class NotificationService
{
    public function sendNotificationToAdmins(Notification $notification)
    {
        $admins = User::where('notifications_enabled', true)->get();
        
        foreach ($admins as $admin) {
            $admin->notify($notification);
        }
    }
}
