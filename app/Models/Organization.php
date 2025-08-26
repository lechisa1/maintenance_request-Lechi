<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    //
    protected $fillable = ['name'];
        public function sectors()
    {
        return $this->hasMany(Sector::class);
    }
}
