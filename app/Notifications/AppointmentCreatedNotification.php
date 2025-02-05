<?php

namespace App\Notifications;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentCreatedNotification extends Notification
{
    use Queueable;
    protected $appointment;
    /**
     * Create a new notification instance.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $patient = Patient::findOrFail($this->appointment->patient_id);
        return (new MailMessage)
        ->subject("New Appointment #{$this->appointment->id} .")
        ->greeting("Hi {$notifiable->name},")
        ->line("A new Appointment (#{$this->appointment->id}) created by {$patient->first_name} {$patient->last_name} .")
        ->action('View Appointment', url('/dashboard'))
        ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
