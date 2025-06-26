<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompletionRejectedNotification extends Notification
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
        $reason = $this->maintenanceRequest->rejection_reason;
        // return [
        //     'message' => 'Your completed maintenance request "' . $this->maintenanceRequest->title . '" was rejected. Reason: ' . $this->maintenanceRequest->rejection_reason,
        // ];
        return [
            'message' => $userName . "" . 'reject completion of your work on  "' . $this->maintenanceRequest->description . " " . "reason" . ":" . $reason,
            'url' => route('requests.show', $this->maintenanceRequest->id),
        ];
    }
}