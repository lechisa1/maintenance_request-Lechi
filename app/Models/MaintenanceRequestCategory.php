<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceRequestCategory extends Model
{
    //
    protected $fillable = [
        'maintenance_request_id',
        'category_id',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignments()
    {
        return $this->hasOne(Assignment::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'maintenance_request_category');
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
    public function technician()
    {
        return $this->hasOneThrough(
            User::class,
            Assignment::class,
            'request_id', // Foreign key on assignments table
            'id', // Foreign key on users table
            'id', // Local key on maintenance_requests table
            'technician_id' // Local key on assignments table
        );
    }
}