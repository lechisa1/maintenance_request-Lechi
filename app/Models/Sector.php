<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    //
    protected $fillable=[
        'name'
    ];
    public function divisions()
{
    return $this->hasMany(Division::class);
}
public function departments()
{
    return $this->hasMany(Department::class);
}
    public function users() {
        return $this->hasMany(User::class);
    }
}
