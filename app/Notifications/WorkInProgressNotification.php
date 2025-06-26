<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WorkInProgressNotification extends Notification
{
    use Queueable;

    protected $maintenanceRequest;

    public function __construct($maintenanceRequest)
    {
        $this->maintenanceRequest = $maintenanceRequest;
    }
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Request submitted by". $this->maintenanceRequest->user->name . 'was started to be solved',
            'url' => route('requests.show', $this->maintenanceRequest->id),            
        ];
    }
}