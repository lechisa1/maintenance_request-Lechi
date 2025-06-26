<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WorkCompletedNotification extends Notification
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
        $latestWorkLog = $this->maintenanceRequest->workLogs()
            ->with('technician')
            ->latest()
            ->first();

        $technicianName = $latestWorkLog?->technician?->name ?? 'Unknown Technician';
        return [
            'message' => "Your maintenance request " . 
                '" has been completed by ' . $technicianName . '.',
            'url' => route('requests.show', $this->maintenanceRequest->id),
        ];
    }
}