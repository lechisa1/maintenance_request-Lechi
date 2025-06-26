<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'department_id',
        'specialization',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }
    public function maintenanceRequests()
    {

        return $this->hasMany(MaintenanceRequest::class);
    }
    public function assignedRequests()
    {
        return $this->hasMany(Assignment::class, 'technician_id');
    }
    public function isDirector()
    {
        return $this->roles->contains('name', 'director');
    }
    public function isTechnician()
    {
        return $this->roles->contains('name', 'technician');
    }
    public function isEmployer()
    {
        return $this->roles->contains('name', 'employer');
    }

    // public function isDirector()
    // {
    //     return $this->role === 'director';
    // }
    // public function isAdmin()
    // {
    //     return $this->roles->contains('name', 'admin');
    // }

    // public function isTechnician()
    // {
    //     return $this->role === 'technician';
    // }

    // public function isEmployer()
    // {
    //     return $this->role === 'employer';
    // }
    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}