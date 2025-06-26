<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestUpdate extends Model
{
    //
        protected $fillable = [
        'request_id', 'user_id', 'update_text', 'update_type'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function maintenanceRequest()
    {
        return $this->belongsTo(MaintenanceRequest::class);
    }
}