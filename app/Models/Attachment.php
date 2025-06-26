<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    //
    protected $fillable = [
        'maintenance_request_id',
        'user_id',
        'file_path',
        'file_type',
        'original_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function maintenanceRequest()
    {
        return $this->belongsTo(MaintenanceRequest::class);
    }
}