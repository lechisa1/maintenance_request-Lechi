<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    //
        protected $fillable = ['name', 'description','director_id','division_id','sector_id'];

    public function users() {
        return $this->hasMany(User::class);
    }
    public function director()
{
    return $this->belongsTo(User::class, 'director_id');
}
public function division()
{
    return $this->belongsTo(Division::class);
}
public function sector()
{
    return $this->belongsTo(Sector::class);
}

}