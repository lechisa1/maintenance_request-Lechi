<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestAcceptedByUserNotification extends Notification
{
    use Queueable;

    public $maintenanceRequest;

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
        $feedback = $this->maintenanceRequest
            ->with('user')
            ->latest()
            ->first();

        $userName = $feedback?->user?->name ?? 'Unknown user';
        // return [
        //     'message' => 'User confirmed completion of request "' . $this->maintenanceRequest->title . '".',
        // ];
        return [
            'message' => $userName . 'confirmed completion of his request maintenance "' . "Thank you!!! for your support",
             'url' => route('requests.show', $this->maintenanceRequest->id),
        ];
    }
}