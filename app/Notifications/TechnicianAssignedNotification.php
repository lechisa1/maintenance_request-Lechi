<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TechnicianAssignedNotification extends Notification
{
    use Queueable;

    public $maintenanceRequest;

    public function __construct($maintenanceRequest)
    {
        $this->maintenanceRequest = $maintenanceRequest;
    }

    public function via($notifiable)
    {
        return ['database']; // Store in database
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'New request assigned to you: ' . $this->maintenanceRequest->user->name,
            'url' => route('technician.show', $this->maintenanceRequest->id),
        ];
    }
}