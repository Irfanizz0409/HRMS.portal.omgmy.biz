<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'clock_in_time',
        'clock_out_time',
        'clock_in_photo',
        'clock_out_photo',
        'clock_in_location',
        'clock_out_location',
        'total_hours',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'total_hours' => 'decimal:2',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Check if user has clocked in today
    public function hasCheckedIn()
    {
        return !is_null($this->clock_in_time);
    }

    // Check if user has clocked out today
    public function hasCheckedOut()
    {
        return !is_null($this->clock_out_time);
    }

    /**
     * Check for incomplete attendance from yesterday and complete it
     */
    public static function handleCrossDayCompletion($userId, $clockOutTime, $clockOutPhoto, $location)
    {
        $yesterday = Carbon::yesterday();
        
        // Find incomplete attendance from yesterday
        $incompleteAttendance = self::where('user_id', $userId)
                                   ->where('date', $yesterday)
                                   ->whereNotNull('clock_in_time')
                                   ->whereNull('clock_out_time')
                                   ->first();
        
        if ($incompleteAttendance) {
            // Complete yesterday's attendance
            $incompleteAttendance->clock_out_time = $clockOutTime;
            $incompleteAttendance->clock_out_photo = $clockOutPhoto;
            $incompleteAttendance->clock_out_location = $location;
            $incompleteAttendance->status = 'cross_day';
            $incompleteAttendance->notes = 'Completed on ' . Carbon::now()->format('Y-m-d H:i:s');
            $incompleteAttendance->calculateTotalHours();
            
            return $incompleteAttendance;
        }
        
        return null;
    }

    /**
 * Enhanced status determination based on admin-configured deadlines
 */
public function determineStatus()
{
    if (!$this->clock_in_time) {
        return 'incomplete';
    }

    $clockInTime = Carbon::createFromFormat('H:i:s', $this->clock_in_time);
    
    // Get user's configurable deadline (not hardcoded 9:45)
    $userDeadline = $this->getUserClockInDeadline();
    $safeTime = Carbon::createFromFormat('H:i:s', $userDeadline);
    
    // Get user's configurable end time
    $userEndTime = $this->getUserPreferredEndTime();
    $standardEndTime = Carbon::createFromFormat('H:i:s', $userEndTime);
    
    // Determine initial status based on admin-set deadline
    if ($clockInTime->lte($safeTime)) {
        $baseStatus = 'safe';
    } else {
        $baseStatus = 'late';
    }
    
    // If clocked out, analyze completion
    if ($this->hasCheckedOut()) {
        $clockOutTime = Carbon::createFromFormat('H:i:s', $this->clock_out_time);
        $userRequiredHours = $this->getUserRequiredHours();
        
        if ($this->total_hours >= $userRequiredHours) {
            // Complete work day
            if ($clockOutTime->gte($standardEndTime)) {
                return $baseStatus === 'safe' ? 'complete' : 'late_complete';
            } else {
                return $baseStatus === 'safe' ? 'early_complete' : 'late_early_complete';
            }
        } else {
            return 'incomplete';
        }
    }
    
    return $baseStatus;
}

/**
 * Get user's configurable clock-in deadline
 */
private function getUserClockInDeadline()
{
    $userSetting = \DB::table('user_work_settings')
                     ->where('user_id', $this->user_id)
                     ->first();
    
    return $userSetting ? $userSetting->clock_in_deadline : '09:45:00';
}

/**
 * Get user's preferred end time
 */
private function getUserPreferredEndTime()
{
    $userSetting = \DB::table('user_work_settings')
                     ->where('user_id', $this->user_id)
                     ->first();
    
    return $userSetting ? $userSetting->preferred_end_time : '18:00:00';
}

    /**
     * Get user-specific required hours
     */
    private function getUserRequiredHours()
    {
        $userSetting = \DB::table('user_work_settings')
                         ->where('user_id', $this->user_id)
                         ->first();
        
        return $userSetting ? (float) $userSetting->required_hours_per_day : 8.0;
    }

    // Calculate working hours when clocking out
    public function calculateTotalHours()
    {
        if ($this->clock_in_time && $this->clock_out_time) {
            // Create full datetime objects for proper calculation
            $clockInDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $this->date->format('Y-m-d') . ' ' . $this->clock_in_time);
            $clockOutDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $this->date->format('Y-m-d') . ' ' . $this->clock_out_time);
            
            // Handle case where clock out is next day (cross-day scenario)
            if ($clockOutDateTime->lt($clockInDateTime)) {
                $clockOutDateTime->addDay();
            }
            
            // Calculate total minutes worked
            $totalMinutes = $clockOutDateTime->diffInMinutes($clockInDateTime);
            
            // Convert to hours with 2 decimal places
            $totalHours = round($totalMinutes / 60, 2);
            
            // Ensure positive hours
            $this->total_hours = max(0, $totalHours);
            
            // Update status based on new logic
            $this->status = $this->determineStatus();
            
            $this->save();
        }
    }

    // Get standard work hours from company settings or default
    private function getStandardWorkHours()
    {
        // Try to get from company settings
        $companySetting = \DB::table('company_settings')
            ->where('key', 'total_work_hours')
            ->first();
            
        return $companySetting ? (float) $companySetting->value : 8.00;
    }

    // Get late penalty time from company settings
    private function getLatePenaltyTime()
    {
        return '09:45:00'; // Fixed based on your requirements
    }

    // Determine if employee is late (after 9:45 AM)
    public function isLate()
    {
        if ($this->clock_in_time) {
            $clockInTime = Carbon::createFromFormat('H:i:s', $this->clock_in_time);
            $latePenaltyTime = Carbon::createFromFormat('H:i:s', $this->getLatePenaltyTime());
            
            return $clockInTime->gt($latePenaltyTime);
        }
        
        return false;
    }

    // Calculate overtime hours
    public function getOvertimeHours()
    {
        if (!$this->total_hours) {
            return 0;
        }
        
        $userRequiredHours = $this->getUserRequiredHours();
        
        if ($this->total_hours > $userRequiredHours) {
            return round(($this->total_hours - $userRequiredHours), 2);
        }
        
        return 0;
    }

    // Get overtime threshold from company settings
    private function getOvertimeThreshold()
    {
        return 30; // 30 minutes threshold
    }

    // Scope for today's attendance
    public function scopeToday($query)
    {
        return $query->where('date', Carbon::today());
    }

    // Scope for specific user
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Scope for date range
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    // Get formatted clock in time
    public function getFormattedClockInAttribute()
    {
        return $this->clock_in_time ? Carbon::createFromFormat('H:i:s', $this->clock_in_time)->format('g:i A') : null;
    }

    // Get formatted clock out time
    public function getFormattedClockOutAttribute()
    {
        return $this->clock_out_time ? Carbon::createFromFormat('H:i:s', $this->clock_out_time)->format('g:i A') : null;
    }

    // Get status badge color for display
    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'safe':
            case 'complete':
                return 'green';
            case 'late':
            case 'late_complete':
            case 'late_early_complete':
                return 'yellow';
            case 'early_complete':
                return 'blue';
            case 'incomplete':
                return 'orange';
            case 'cross_day':
                return 'purple';
            case 'absent':
                return 'red';
            default:
                return 'gray';
        }
    }

    // Get working hours display
    public function getWorkingHoursDisplayAttribute()
    {
        if (!$this->total_hours) {
            return '0.00 hours';
        }
        
        $hours = floor($this->total_hours);
        $minutes = round(($this->total_hours - $hours) * 60);
        
        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$minutes}m";
        }
    }
}