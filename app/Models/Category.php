<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
        protected $fillable = ['name', 'description'];

    public function maintenanceRequests()
    {
        return $this->belongsToMany(MaintenanceRequest::class, 'maintenance_request_categories');
    }
        public function items()
    {
        return $this->belongsToMany(Item::class, 'category_item');
    }
}