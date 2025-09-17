<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserWorkSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminWorkSettingsController extends Controller
{
    /**
     * Display work settings for all users (Admin/HR only)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Check permissions
        if (!in_array($user->role, ['admin', 'hr'])) {
            abort(403, 'Unauthorized access.');
        }
        
        $query = User::with('workSetting')->where('employment_status', 'active');
        
        // Filter by role if specified
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        // Filter by department if specified
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }
        
        $users = $query->orderBy('name')->paginate(20);
        
        // Get unique departments and roles for filters
        $departments = User::whereNotNull('department')->distinct()->pluck('department');
        $roles = ['staff', 'intern', 'part_time'];
        
        return view('admin.work-settings', compact('users', 'departments', 'roles'));
    }

    /**
     * Show form to edit user work settings
     */
    public function edit(User $user)
    {
        $authUser = Auth::user();
        
        // Check permissions
        if (!in_array($authUser->role, ['admin', 'hr'])) {
            abort(403, 'Unauthorized access.');
        }
        
        $workSetting = UserWorkSetting::getOrCreateForUser($user->id);
        
        return view('admin.edit-work-settings', compact('user', 'workSetting'));
    }

    /**
     * Update user work settings
     */
    public function update(Request $request, User $user)
    {
        $authUser = Auth::user();
        
        // Check permissions
        if (!in_array($authUser->role, ['admin', 'hr'])) {
            abort(403, 'Unauthorized access.');
        }
        
        $request->validate([
            'required_hours_per_day' => 'required|numeric|min:1|max:24',
            'preferred_start_time' => 'required|date_format:H:i',
            'preferred_end_time' => 'required|date_format:H:i|after:preferred_start_time',
            'clock_in_deadline' => 'required|date_format:H:i',
            'flexible_timing' => 'boolean',
            'overtime_threshold' => 'required|numeric|min:0|max:24',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $workSetting = UserWorkSetting::getOrCreateForUser($user->id);
        
        $workSetting->update([
            'required_hours_per_day' => $request->required_hours_per_day,
            'preferred_start_time' => $request->preferred_start_time,
            'preferred_end_time' => $request->preferred_end_time,
            'clock_in_deadline' => $request->clock_in_deadline,
            'flexible_timing' => $request->has('flexible_timing'),
            'overtime_threshold' => $request->overtime_threshold,
            'notes' => $request->notes,
        ]);
        
        return redirect()
            ->route('admin.work-settings.index')
            ->with('success', "Work settings updated successfully for {$user->name}.");
    }

    /**
     * Bulk update work settings for multiple users
     */
    public function bulkUpdate(Request $request)
    {
        $authUser = Auth::user();
        
        // Check permissions
        if (!in_array($authUser->role, ['admin', 'hr'])) {
            abort(403, 'Unauthorized access.');
        }
        
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'required_hours_per_day' => 'required|numeric|min:1|max:24',
            'preferred_start_time' => 'required|date_format:H:i',
            'preferred_end_time' => 'required|date_format:H:i|after:preferred_start_time',
            'clock_in_deadline' => 'required|date_format:H:i',
            'flexible_timing' => 'boolean',
            'overtime_threshold' => 'required|numeric|min:0|max:24',
        ]);
        
        $updateData = [
            'required_hours_per_day' => $request->required_hours_per_day,
            'preferred_start_time' => $request->preferred_start_time,
            'preferred_end_time' => $request->preferred_end_time,
            'clock_in_deadline' => $request->clock_in_deadline,
            'flexible_timing' => $request->has('flexible_timing'),
            'overtime_threshold' => $request->overtime_threshold,
        ];
        
        $updatedCount = 0;
        
        foreach ($request->user_ids as $userId) {
            $workSetting = UserWorkSetting::getOrCreateForUser($userId);
            $workSetting->update($updateData);
            $updatedCount++;
        }
        
        return redirect()
            ->route('admin.work-settings.index')
            ->with('success', "Work settings updated successfully for {$updatedCount} employees.");
    }

    /**
     * Reset work settings to default for a user
     */
    public function reset(User $user)
    {
        $authUser = Auth::user();
        
        // Check permissions
        if (!in_array($authUser->role, ['admin', 'hr'])) {
            abort(403, 'Unauthorized access.');
        }
        
        $workSetting = UserWorkSetting::where('user_id', $user->id)->first();
        
        if ($workSetting) {
            $workSetting->update([
                'required_hours_per_day' => 8.00,
                'preferred_start_time' => '10:00:00',
                'preferred_end_time' => '18:00:00',
                'clock_in_deadline' => '09:45:00',
                'flexible_timing' => false,
                'overtime_threshold' => 8.00,
                'notes' => null,
            ]);
        }
        
        return redirect()
            ->route('admin.work-settings.index')
            ->with('success', "Work settings reset to default for {$user->name}.");
    }
}