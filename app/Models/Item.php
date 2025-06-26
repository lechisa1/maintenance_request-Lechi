<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //
     protected $fillable = ['name', 'unit', 'in_stock'];
        public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }
    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }
}