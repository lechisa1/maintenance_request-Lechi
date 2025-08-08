<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    //
       protected $fillable = ['name', 'sector_id'];
    public function sector()
{
    return $this->belongsTo(Sector::class);
}

public function departments()
{
    return $this->hasMany(Department::class);
}
    public function users() {
        return $this->hasMany(User::class);
    }

}
