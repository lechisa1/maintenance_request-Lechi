<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkLog extends Model
{
    //
    protected $fillable = [
        'maintenance_request_id',
        'technician_id',
        'work_done',
        'materials_used',
        'completion_notes',
        'time_spent_minutes',
        'log_date'
    ];

    public function technician()
    {
        return $this->belongsTo(User::class);
    }

    public function maintenanceRequest()
    {
        return $this->belongsTo(MaintenanceRequest::class);
    }
}