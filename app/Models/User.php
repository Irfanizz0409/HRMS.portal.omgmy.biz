<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'employee_id',
        'ic_number',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'department',
        'position',
        'hire_date',
        'salary',
        'employment_status',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_phone',
        'profile_photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'salary', // Hide salary from general queries
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'hire_date' => 'date',
            'last_login' => 'datetime',
            'salary' => 'decimal:2',
        ];
    }

    // Accessor for full name with employee ID
    public function getFullIdentityAttribute()
    {
        return $this->employee_id ? "{$this->name} ({$this->employee_id})" : $this->name;
    }

    // Check if user is active
    public function isActive()
    {
        return $this->employment_status === 'active';
    }

    // Check if user is blocked
    public function isBlocked()
    {
        return $this->employment_status === 'blocked';
    }

    // Scope for active employees only
    public function scopeActive($query)
    {
        return $query->where('employment_status', 'active');
    }

    // Scope by role
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // Scope by department
    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }
}