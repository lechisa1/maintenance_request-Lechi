<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasRoles;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar_url',
        'password',
        'phone',
        'department_id',
        'specialization',
        'job_position_id',
        'reports_to',
        'sector_id',
        'division_id'

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
        public function sector()
    {
        return $this->belongsTo(Sector::class);
    }
        public function division()
    {
        return $this->belongsTo(Division::class);
    }
public function directedDepartment()
{
    return $this->hasOne(Department::class, 'director_id');
}
    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    // }
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
    return $this->hasRole('director');
}

public function isTechnician()
{
    return $this->hasRole('technician');
}

public function isEmployer()
{
    return $this->hasRole('employer');
}
// Supervisor (manager)
// Who this user reports to (supervisor)
public function reportsTo()
{
    return $this->belongsTo(User::class, 'reports_to');
}
    public function jobPosition()
{
    return $this->belongsTo(JobPosition::class,'job_position_id');
}
// Users who report to this user (subordinates)
public function subordinates()
{
    return $this->hasMany(User::class, 'reports_to');
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