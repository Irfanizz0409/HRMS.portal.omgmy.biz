<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWorkSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'required_hours_per_day',
        'preferred_start_time',
        'preferred_end_time',
        'clock_in_deadline',
        'flexible_timing',
        'overtime_threshold',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'required_hours_per_day' => 'decimal:2',
            'overtime_threshold' => 'decimal:2',
            'flexible_timing' => 'boolean',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get or create default settings for user
    public static function getOrCreateForUser($userId)
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            [
                'required_hours_per_day' => 8.00,
                'preferred_start_time' => '10:00:00',
                'preferred_end_time' => '18:00:00',
                'clock_in_deadline' => '09:45:00',
                'flexible_timing' => false,
                'overtime_threshold' => 8.00,
            ]
        );
    }

    // Get formatted times for display
    public function getFormattedPreferredStartAttribute()
    {
        return $this->preferred_start_time ? date('g:i A', strtotime($this->preferred_start_time)) : null;
    }

    public function getFormattedPreferredEndAttribute()
    {
        return $this->preferred_end_time ? date('g:i A', strtotime($this->preferred_end_time)) : null;
    }

    public function getFormattedClockInDeadlineAttribute()
    {
        return $this->clock_in_deadline ? date('g:i A', strtotime($this->clock_in_deadline)) : null;
    }
}