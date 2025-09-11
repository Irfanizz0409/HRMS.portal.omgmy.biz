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

    // Calculate working hours when clocking out
    public function calculateTotalHours()
    {
        if ($this->clock_in_time && $this->clock_out_time) {
            // Create full datetime objects for proper calculation
            $clockInDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $this->date->format('Y-m-d') . ' ' . $this->clock_in_time);
            $clockOutDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $this->date->format('Y-m-d') . ' ' . $this->clock_out_time);
            
            // Handle case where clock out is next day (night shift)
            if ($clockOutDateTime->lt($clockInDateTime)) {
                $clockOutDateTime->addDay();
            }
            
            // Calculate total minutes worked
            $totalMinutes = $clockOutDateTime->diffInMinutes($clockInDateTime);
            
            // Convert to hours with 2 decimal places
            $totalHours = round($totalMinutes / 60, 2);
            
            // Ensure positive hours
            $this->total_hours = max(0, $totalHours);
            
            // Get company standard hours (default 9 hours)
            $standardHours = $this->getStandardWorkHours();
            
            // Update status based on hours worked
            if ($this->total_hours >= $standardHours) {
                $this->status = 'present';
            } elseif ($this->total_hours >= ($standardHours * 0.5)) {
                $this->status = 'incomplete';
            } else {
                $this->status = 'absent';
            }
            
            // Check if late
            if ($this->isLate()) {
                $this->status = $this->status === 'present' ? 'late' : $this->status;
            }
            
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
            
        return $companySetting ? (float) $companySetting->value : 9.00;
    }

    // Get late penalty time from company settings
    private function getLatePenaltyTime()
    {
        $companySetting = \DB::table('company_settings')
            ->where('key', 'late_penalty_after')
            ->first();
            
        return $companySetting ? $companySetting->value : '09:46:00';
    }

    // Determine if employee is late
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
        
        $standardHours = $this->getStandardWorkHours();
        $overtimeThreshold = $this->getOvertimeThreshold();
        
        if ($this->total_hours > $standardHours) {
            $overtimeMinutes = ($this->total_hours - $standardHours) * 60;
            
            // Only count as overtime if exceeds threshold (default 30 minutes)
            if ($overtimeMinutes >= $overtimeThreshold) {
                return round(($overtimeMinutes / 60), 2);
            }
        }
        
        return 0;
    }

    // Get overtime threshold from company settings
    private function getOvertimeThreshold()
    {
        $companySetting = \DB::table('company_settings')
            ->where('key', 'ot_threshold_minutes')
            ->first();
            
        return $companySetting ? (int) $companySetting->value : 30;
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
            case 'present':
                return 'green';
            case 'late':
                return 'yellow';
            case 'incomplete':
                return 'orange';
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