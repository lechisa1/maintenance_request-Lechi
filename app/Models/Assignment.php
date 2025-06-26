<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    //
    protected $casts = [
        'requested_at' => 'datetime',
        'assigned_at' => 'datetime',
        'expected_completion_date' => 'datetime',
    ];
    protected $fillable = [
        'maintenance_request_id',
        'director_id',
        'technician_id',
        'director_notes',
        'assigned_at',
        'expected_completion_date'
    ];

    public function maintenanceRequest()
    {
        return $this->belongsTo(MaintenanceRequest::class);
    }


    public function director()
    {
        return $this->belongsTo(User::class, 'director_id');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}