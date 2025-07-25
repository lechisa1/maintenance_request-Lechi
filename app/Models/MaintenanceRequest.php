<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    //
    protected $casts = [
        'requested_at' => 'datetime',
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
        //  'attachments' => 'array',
    ];
    


    protected $fillable = [
        'user_id',
        'item_id',
        'description',
        // 'location',
        'priority',
        'status',
        'requested_at',
        'completed_at',
        'ticket_number',
        'user_feedback',
         'supervisor_status',
        'rejection_reason',
        'category_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'maintenance_request_id');
    }


    public function categories()
    {
        return $this->belongsToMany(Category::class, 'maintenance_request_categories');
    }


    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function updates()
    {
        return $this->hasMany(RequestUpdate::class);
    }

    public function workLogs()
    {
        return $this->hasMany(WorkLog::class);
    }
    public function latestAssignment()
    {
        return $this->hasOne(Assignment::class, 'maintenance_request_id')->latestOfMany();
    }
    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}
