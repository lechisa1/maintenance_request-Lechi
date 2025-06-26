<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestCreatedNotification extends Notification
{
    use Queueable;

    public $maintenanceRequest;

    public function __construct($maintenanceRequest)
    {
        $this->maintenanceRequest = $maintenanceRequest;
    }

    public function via($notifiable)
    {
        // Channels: database and mail (you can add more like Slack, SMS, etc.)
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'A new maintenance request has been submitted by : ' . $this->maintenanceRequest->user->name,
            'url' => route('requests.show', $this->maintenanceRequest->id),

        ];
    }
}