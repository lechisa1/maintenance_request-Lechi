<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceNotFixedNotification extends Notification
{
    use Queueable;
    protected $maintenanceRequest;
    /**
     * Create a new notification instance.
     */
    public function __construct($maintenanceRequest)
    {
        $this->maintenanceRequest = $maintenanceRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            "message" =>"The Request Submitted by :".$this->maintenanceRequest->user->name." "."unfortunately unable to be solved",
            'url' => route('requests.show', $this->maintenanceRequest->id),
        ];
    }
}