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
        'working_experience',
        'academic_background',
        // Enhanced auth fields
        'remember_expires_at',
        'trusted_devices',
        'last_login_at',
        'last_login_ip',
        'last_login_user_agent',
        'two_factor_enabled',
        'login_preferences',
        'active_sessions_count',
        'session_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'salary', // Hide salary from general queries
        'trusted_devices', // Hide device fingerprints
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
            // Enhanced auth casts
            'remember_expires_at' => 'datetime',
            'trusted_devices' => 'array',
            'last_login_at' => 'datetime',
            'login_preferences' => 'array',
            'session_expires_at' => 'datetime',
            'two_factor_enabled' => 'boolean',
        ];
    }

    // Relationships
    /**
     * Get the user's work settings.
     */
    public function workSetting()
    {
        return $this->hasOne(UserWorkSetting::class);
    }

    /**
     * Get the user's attendance records.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the user's login activities.
     */
    public function loginActivities()
    {
        return $this->hasMany(LoginActivity::class);
    }

    // Accessors
    public function getFullIdentityAttribute()
    {
        return $this->employee_id ? "{$this->name} ({$this->employee_id})" : $this->name;
    }

    /**
     * Get user's required hours per day with fallback to default
     */
    public function getRequiredHoursAttribute()
    {
        return $this->workSetting ? $this->workSetting->required_hours_per_day : 8.00;
    }

    /**
     * Get user's clock-in deadline with fallback to default
     */
    public function getClockInDeadlineAttribute()
    {
        return $this->workSetting ? $this->workSetting->clock_in_deadline : '09:45:00';
    }

    /**
     * Get formatted last login time
     */
    public function getLastLoginAttribute(): string
    {
        if (!$this->last_login_at) {
            return 'Never';
        }
        
        return $this->last_login_at->diffForHumans();
    }

    // Work Setting Methods
    /**
     * Check if user has flexible timing
     */
    public function hasFlexibleTiming()
    {
        return $this->workSetting ? $this->workSetting->flexible_timing : false;
    }

    /**
     * Get user's overtime threshold
     */
    public function getOvertimeThresholdAttribute()
    {
        return $this->workSetting ? $this->workSetting->overtime_threshold : 8.00;
    }

    // Employment Status Methods
    public function isActive()
    {
        return $this->employment_status === 'active';
    }

    public function isBlocked()
    {
        return $this->employment_status === 'blocked';
    }

    // Enhanced Authentication Methods
    /**
     * Check if remember token is expired
     */
    public function isRememberTokenExpired(): bool
    {
        return $this->remember_expires_at && $this->remember_expires_at->isPast();
    }

    /**
     * Check if device is trusted
     */
    public function isDeviceTrusted(string $fingerprint): bool
    {
        if (!$this->trusted_devices) {
            return false;
        }
        
        foreach ($this->trusted_devices as $device) {
            if ($device['fingerprint'] === $fingerprint && 
                \Carbon\Carbon::parse($device['expires_at'])->isFuture()) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Add a trusted device
     */
    public function addTrustedDevice(array $deviceData): void
    {
        $trustedDevices = $this->trusted_devices ?? [];
        
        $trustedDevices[] = array_merge($deviceData, [
            'trusted_at' => now(),
            'expires_at' => now()->addDays(90), // Trust for 90 days
        ]);
        
        // Keep only last 5 trusted devices
        $trustedDevices = array_slice($trustedDevices, -5);
        
        $this->update(['trusted_devices' => $trustedDevices]);
    }

    /**
     * Clean expired trusted devices
     */
    public function cleanExpiredTrustedDevices(): void
    {
        if (!$this->trusted_devices) {
            return;
        }
        
        $validDevices = collect($this->trusted_devices)
            ->filter(function ($device) {
                return \Carbon\Carbon::parse($device['expires_at'])->isFuture();
            })
            ->values()
            ->toArray();
        
        $this->update(['trusted_devices' => $validDevices]);
    }

    /**
     * Update login activity
     */
    public function updateLoginActivity(array $data): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $data['ip_address'] ?? null,
            'last_login_user_agent' => $data['user_agent'] ?? null,
        ]);
    }

    /**
     * Get recent login activities
     */
    public function getRecentLoginActivities(int $limit = 10)
    {
        return $this->loginActivities()
                   ->latest('login_at')
                   ->limit($limit)
                   ->get();
    }

    /**
     * Check if session is expired based on role
     */
    public function isSessionExpired(): bool
    {
        if (!$this->session_expires_at) {
            return false;
        }
        
        return $this->session_expires_at->isPast();
    }

    /**
     * Get session timeout for user role
     */
    public function getSessionTimeout(): int
    {
        $roleLifetimes = config('session.role_lifetime', []);
        return $roleLifetimes[$this->role] ?? config('session.lifetime', 120);
    }

    /**
     * Increment active sessions count
     */
    public function incrementActiveSessions(): void
    {
        $this->increment('active_sessions_count');
    }

    /**
     * Decrement active sessions count
     */
    public function decrementActiveSessions(): void
    {
        if ($this->active_sessions_count > 0) {
            $this->decrement('active_sessions_count');
        }
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('employment_status', 'active');
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Scope for users with expired sessions
     */
    public function scopeWithExpiredSessions($query)
    {
        return $query->where('session_expires_at', '<', now());
    }

    /**
     * Scope for users with active sessions
     */
    public function scopeWithActiveSessions($query)
    {
        return $query->where('active_sessions_count', '>', 0);
    }
}