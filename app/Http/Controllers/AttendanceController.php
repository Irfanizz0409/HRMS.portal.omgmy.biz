<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Show attendance dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        // Get today's attendance record
        $todayAttendance = Attendance::where('user_id', $user->id)
                                   ->where('date', $today)
                                   ->first();
        
        // Get recent attendance records (last 7 days)
        $recentAttendance = Attendance::where('user_id', $user->id)
                                    ->where('date', '>=', $today->copy()->subDays(6))
                                    ->orderBy('date', 'desc')
                                    ->get();
        
        return view('attendance.index', compact('todayAttendance', 'recentAttendance'));
    }

    /**
     * Show clock in/out interface
     */
    public function clockInOut()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        // Get or create today's attendance record
        $attendance = Attendance::firstOrCreate(
            [
                'user_id' => $user->id,
                'date' => $today
            ],
            [
                'status' => 'incomplete'
            ]
        );
        
        return view('attendance.clock', compact('attendance'));
    }

    /**
     * Process clock in
     */
    public function clockIn(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();
        $now = Carbon::now();
        
        // Check if already clocked in today
        $attendance = Attendance::where('user_id', $user->id)
                                ->where('date', $today)
                                ->first();
        
        if ($attendance && $attendance->hasCheckedIn()) {
            return response()->json([
                'success' => false,
                'message' => 'You have already clocked in today.'
            ]);
        }
        
        // Validate photo
        $request->validate([
            'photo' => 'required|string', // Base64 image data
            'location' => 'nullable|string'
        ]);
        
        // Process and save photo
        $photoPath = $this->saveBase64Image($request->photo, 'clock_in_' . $user->id . '_' . $today->format('Y-m-d'));
        
        // Create or update attendance record
        if (!$attendance) {
            $attendance = new Attendance([
                'user_id' => $user->id,
                'date' => $today
            ]);
        }
        
        $attendance->clock_in_time = $now->format('H:i:s');
        $attendance->clock_in_photo = $photoPath;
        $attendance->clock_in_location = $request->location;
        
        // Determine if late (after 9:00 AM)
        $standardTime = Carbon::parse('09:00:00');
        if ($now->gt($standardTime)) {
            $attendance->status = 'late';
        } else {
            $attendance->status = 'present';
        }
        
        $attendance->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Clock in successful!',
            'time' => $now->format('g:i A'),
            'status' => $attendance->status
        ]);
    }

    /**
     * Process clock out
     */
    public function clockOut(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();
        $now = Carbon::now();
        
        // Get today's attendance record
        $attendance = Attendance::where('user_id', $user->id)
                                ->where('date', $today)
                                ->first();
        
        if (!$attendance || !$attendance->hasCheckedIn()) {
            return response()->json([
                'success' => false,
                'message' => 'You must clock in first before clocking out.'
            ]);
        }
        
        if ($attendance->hasCheckedOut()) {
            return response()->json([
                'success' => false,
                'message' => 'You have already clocked out today.'
            ]);
        }
        
        // Validate photo
        $request->validate([
            'photo' => 'required|string', // Base64 image data
            'location' => 'nullable|string'
        ]);
        
        // Process and save photo
        $photoPath = $this->saveBase64Image($request->photo, 'clock_out_' . $user->id . '_' . $today->format('Y-m-d'));
        
        // Update attendance record
        $attendance->clock_out_time = $now->format('H:i:s');
        $attendance->clock_out_photo = $photoPath;
        $attendance->clock_out_location = $request->location;
        
        // Calculate total working hours
        $attendance->calculateTotalHours();
        
        return response()->json([
            'success' => true,
            'message' => 'Clock out successful!',
            'time' => $now->format('g:i A'),
            'total_hours' => $attendance->total_hours,
            'status' => $attendance->status
        ]);
    }

    /**
     * View attendance history
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        
        // Date range filter
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());
        
        $attendances = Attendance::where('user_id', $user->id)
                                ->dateRange($startDate, $endDate)
                                ->orderBy('date', 'desc')
                                ->paginate(15);
        
        return view('attendance.history', compact('attendances', 'startDate', 'endDate'));
    }

    /**
     * Admin/HR view all employee attendance
     */
    public function adminIndex(Request $request)
    {
        $user = Auth::user();
        
        // Check permissions
        if (!in_array($user->role, ['admin', 'hr'])) {
            abort(403, 'Unauthorized access.');
        }
        
        $today = Carbon::today();
        $query = Attendance::with('user')->where('date', $today);
        
        // Filter by employee if specified
        if ($request->filled('employee_id')) {
            $query->where('user_id', $request->employee_id);
        }
        
        $attendances = $query->orderBy('clock_in_time', 'asc')->get();
        
        // Get employee list for filter
        $employees = \App\Models\User::where('employment_status', 'active')
                                   ->whereIn('role', ['staff', 'intern', 'part_time'])
                                   ->orderBy('name')
                                   ->get();
        
        return view('attendance.admin', compact('attendances', 'employees'));
    }

    /**
     * Save base64 image to storage
     */
    private function saveBase64Image($base64String, $filename)
    {
        // Remove data:image/jpeg;base64, prefix if present
        if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $type)) {
            $base64String = substr($base64String, strpos($base64String, ',') + 1);
            $fileExtension = strtolower($type[1]); // jpg, png, gif
        } else {
            $fileExtension = 'jpg'; // default
        }
        
        // Decode base64
        $imageData = base64_decode($base64String);
        
        if ($imageData === false) {
            throw new \Exception('Failed to decode base64 image');
        }
        
        // Generate unique filename
        $filename = $filename . '_' . time() . '.' . $fileExtension;
        $path = 'attendance_photos/' . $filename;
        
        // Save to storage
        Storage::disk('public')->put($path, $imageData);
        
        return $path;
    }

    /**
     * Display attendance photo
     */
    public function showPhoto($filename)
    {
        $path = 'attendance_photos/' . $filename;
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }
        
        return Storage::disk('public')->response($path);
    }


    /**
 * Delete attendance record (Admin/HR only)
 */
public function destroy(Attendance $attendance)
{
    $user = Auth::user();
    
    // Check permissions
    if (!in_array($user->role, ['admin', 'hr'])) {
        abort(403, 'Unauthorized access.');
    }
    
    // Delete associated photos if they exist
    if ($attendance->clock_in_photo) {
        Storage::disk('public')->delete($attendance->clock_in_photo);
    }
    
    if ($attendance->clock_out_photo) {
        Storage::disk('public')->delete($attendance->clock_out_photo);
    }
    
    $attendance->delete();
    
    return redirect()->route('attendance.admin')
        ->with('success', 'Attendance record deleted successfully.');
}

/**
 * Admin update attendance record
 */
public function adminUpdate(Request $request, Attendance $attendance)
{
    $user = Auth::user();
    
    // Check permissions
    if (!in_array($user->role, ['admin', 'hr'])) {
        abort(403, 'Unauthorized access.');
    }
    
    // Validate input
    $request->validate([
        'clock_in_time' => 'nullable|date_format:H:i',
        'clock_out_time' => 'nullable|date_format:H:i',
        'status' => 'required|in:present,late,absent,incomplete',
        'notes' => 'nullable|string|max:500'
    ]);
    
    // Update attendance record
    $attendance->update([
        'clock_in_time' => $request->clock_in_time,
        'clock_out_time' => $request->clock_out_time,
        'status' => $request->status,
        'notes' => $request->notes
    ]);
    
    // Recalculate total hours if both times are present
    if ($attendance->clock_in_time && $attendance->clock_out_time) {
        $attendance->calculateTotalHours();
    }
    
    return redirect()->route('attendance.admin')
                    ->with('success', 'Attendance record updated successfully.');
}

/**
 * Get attendance photos for display
 */
public function getPhotos(Attendance $attendance)
{
    $user = Auth::user();
    
    // Check permissions - admin/hr can view all, employees can view their own
    if (!in_array($user->role, ['admin', 'hr']) && $attendance->user_id !== $user->id) {
        return response()->json(['success' => false, 'message' => 'Unauthorized']);
    }
    
    return response()->json([
        'success' => true,
        'clock_in_photo' => $attendance->clock_in_photo,
        'clock_out_photo' => $attendance->clock_out_photo,
    ]);
}
}