<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'login_at',
        'logout_at',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'platform',
        'device_fingerprint',
        'session_id',
        'remember_option',
        'device_trusted',
        'session_duration_minutes',
        'is_suspicious',
        'logout_reason',
        'additional_data',
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
        'device_trusted' => 'boolean',
        'is_suspicious' => 'boolean',
        'additional_data' => 'array',
    ];

    /**
     * Get the user that owns the login activity.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate session duration
     */
    public function getSessionDurationAttribute(): ?string
    {
        if (!$this->logout_at || !$this->login_at) {
            return null;
        }
        
        return $this->login_at->diffForHumans($this->logout_at, true);
    }

    /**
     * Check if login is suspicious
     */
    public function scopeSuspicious($query)
    {
        return $query->where('is_suspicious', true);
    }

    /**
     * Scope for active sessions (no logout)
     */
    public function scopeActiveSessions($query)
    {
        return $query->whereNull('logout_at');
    }
}