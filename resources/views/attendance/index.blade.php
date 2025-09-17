@extends('layouts.sidebar')

@section('page-title', 'My Attendance Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Today's Status -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Today's Status</h3>
            @if($todayAttendance)
                @if($todayAttendance->hasCheckedIn() && $todayAttendance->hasCheckedOut())
                    <div class="text-2xl font-bold text-green-600">Complete</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ $todayAttendance->working_hours_display }}</div>
                @elseif($todayAttendance->hasCheckedIn())
                    <div class="text-2xl font-bold text-blue-600">Clocked In</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Since {{ $todayAttendance->formatted_clock_in }}</div>
                @else
                    <div class="text-2xl font-bold text-gray-600">Not Started</div>
                @endif
            @else
                <div class="text-2xl font-bold text-gray-600">Not Started</div>
            @endif
        </div>

        <!-- This Week -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">This Week</h3>
            <div class="text-2xl font-bold text-blue-600">{{ $recentAttendance->count() }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Days attended</div>
        </div>

        <!-- Total Hours -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Weekly Hours</h3>
            <div class="text-2xl font-bold text-blue-600">
                {{ number_format($recentAttendance->sum('total_hours'), 1) }}h
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Total worked</div>
        </div>

        <!-- Average Hours -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Average Daily</h3>
            <div class="text-2xl font-bold text-blue-600">
                @if($recentAttendance->count() > 0)
                    {{ number_format($recentAttendance->avg('total_hours'), 1) }}h
                @else
                    0h
                @endif
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Per day</div>
        </div>
    </div>

    <!-- Today's Detailed Status -->
    @if($todayAttendance)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Today's Details - {{ today()->format('M d, Y') }}</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Clock In -->
                <div class="text-center bg-gray-50 dark:bg-gray-700 p-6 rounded-xl">
                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Clock In</div>
                    <div class="text-3xl font-bold text-green-600">
                        {{ $todayAttendance->formatted_clock_in ?? 'Not clocked in' }}
                    </div>
                    @if($todayAttendance->hasCheckedIn())
                        @php
                            $userSettings = \App\Models\UserWorkSetting::where('user_id', auth()->id())->first();
                            $deadline = $userSettings ? $userSettings->clock_in_deadline : '09:45:00';
                            $clockInTime = \Carbon\Carbon::createFromFormat('H:i:s', $todayAttendance->clock_in_time);
                            $deadlineTime = \Carbon\Carbon::createFromFormat('H:i:s', $deadline);
                            $isLate = $clockInTime->gt($deadlineTime);
                            $deadlineDisplay = date('g:i A', strtotime($deadline));
                        @endphp
                        
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                            @if($isLate)
                                <span class="text-red-600 font-medium">Late Arrival (After {{ $deadlineDisplay }})</span>
                            @else
                                <span class="text-green-600 font-medium">On Time</span>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Clock Out -->
                <div class="text-center bg-gray-50 dark:bg-gray-700 p-6 rounded-xl">
                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Clock Out</div>
                    <div class="text-3xl font-bold text-red-600">
                        {{ $todayAttendance->formatted_clock_out ?? 'Not clocked out' }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-2">Regular Hours</div>
                </div>

                <!-- Hours Worked -->
                <div class="text-center bg-gray-50 dark:bg-gray-700 p-6 rounded-xl">
                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hours Worked</div>
                    @if($todayAttendance && $todayAttendance->hasCheckedIn() && !$todayAttendance->hasCheckedOut())
                        <div class="text-3xl font-bold text-green-600" id="dashboard-timer">
                            00:00:00
                        </div>
                        <div class="text-sm text-green-600 mt-2">Currently working</div>
                    @else
                        <div class="text-3xl font-bold text-blue-600">
                            @if($todayAttendance && $todayAttendance->total_hours)
                                {{ number_format($todayAttendance->total_hours, 1) }}h
                            @else
                                0h
                            @endif
                        </div>
                        @php
                            $userSettings = \App\Models\UserWorkSetting::where('user_id', auth()->id())->first();
                            $requiredHours = $userSettings ? $userSettings->required_hours_per_day : 8.00;
                        @endphp
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                            @if($todayAttendance && $todayAttendance->total_hours >= $requiredHours)
                                <span class="text-green-600 font-medium">Complete</span>
                            @elseif($todayAttendance && $todayAttendance->total_hours > 0)
                                <span class="text-orange-600 font-medium">Incomplete</span>
                            @else
                                <span class="text-gray-600">Not started</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Work Schedule Info -->
        <div class="border-t border-gray-200 dark:border-gray-600 p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                @php
                    $userSettings = \App\Models\UserWorkSetting::where('user_id', auth()->id())->first();
                    $clockInDeadline = $userSettings ? date('g:i A', strtotime($userSettings->clock_in_deadline)) : '9:45 AM';
                    $startTime = $userSettings ? date('g:i A', strtotime($userSettings->preferred_start_time)) : '10:00 AM';
                    $endTime = $userSettings ? date('g:i A', strtotime($userSettings->preferred_end_time)) : '6:00 PM';
                    $requiredHours = $userSettings ? $userSettings->required_hours_per_day : '8.00';
                @endphp
                
                <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-lg">
                    <div class="text-sm text-blue-700 dark:text-blue-300">Clock In Deadline</div>
                    <div class="text-lg font-bold text-blue-800 dark:text-blue-200">{{ $clockInDeadline }}</div>
                </div>
                <div class="bg-green-100 dark:bg-green-900 p-4 rounded-lg">
                    <div class="text-sm text-green-700 dark:text-green-300">Work Hours</div>
                    <div class="text-lg font-bold text-green-800 dark:text-green-200">{{ $startTime }} - {{ $endTime }}</div>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900 p-4 rounded-lg">
                    <div class="text-sm text-purple-700 dark:text-purple-300">Required Hours</div>
                    <div class="text-lg font-bold text-purple-800 dark:text-purple-200">{{ $requiredHours }} hours/day</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Attendance -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-600">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Recent Attendance (Last 7 Days)</h3>
                <a href="{{ route('attendance.history') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    View Full History
                </a>
            </div>
        </div>

        @if($recentAttendance->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Clock In</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Clock Out</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Hours</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($recentAttendance as $attendance)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $attendance->date->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $attendance->date->format('l') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $attendance->formatted_clock_in ?? '-' }}</div>
                                    @if($attendance->hasCheckedIn())
                                        @php
                                            $userSettings = \App\Models\UserWorkSetting::where('user_id', auth()->id())->first();
                                            $deadline = $userSettings ? $userSettings->clock_in_deadline : '09:45:00';
                                            $clockInTime = \Carbon\Carbon::createFromFormat('H:i:s', $attendance->clock_in_time);
                                            $deadlineTime = \Carbon\Carbon::createFromFormat('H:i:s', $deadline);
                                            $isLate = $clockInTime->gt($deadlineTime);
                                        @endphp
                                        
                                        @if($isLate)
                                            <div class="text-xs text-red-600">Late</div>
                                        @else
                                            <div class="text-xs text-green-600">On Time</div>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $attendance->formatted_clock_out ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $attendance->working_hours_display ?? '0h' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($attendance->status == 'safe') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                        @elseif($attendance->status == 'late') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                        @elseif($attendance->status == 'complete') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                        @elseif($attendance->status == 'late_complete') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                        @elseif($attendance->status == 'early_complete') bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-300
                                        @elseif($attendance->status == 'incomplete') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-500 dark:text-gray-400 text-lg mb-4">
                    No recent attendance records found.
                </div>
                <a href="{{ route('attendance.clock') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Start Attendance
                </a>
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="text-center space-x-4">
        <a href="{{ route('attendance.clock') }}" 
           class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 px-8 rounded-lg inline-block shadow-lg transition-all duration-300 transform hover:scale-105">
            Clock In/Out
        </a>
        <a href="{{ route('attendance.history') }}" 
           class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-bold py-3 px-8 rounded-lg inline-block shadow-lg transition-all duration-300 transform hover:scale-105">
            Full History
        </a>
    </div>
</div>

<script>
@if($todayAttendance && $todayAttendance->hasCheckedIn() && !$todayAttendance->hasCheckedOut())
function startDashboardTimer() {
    const clockInTime = new Date('{{ $todayAttendance->date->format("Y-m-d") }} {{ $todayAttendance->clock_in_time }}');
    const timerElement = document.getElementById('dashboard-timer');
    
    function updateTimer() {
        const now = new Date();
        const diff = now - clockInTime;
        
        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
        
        const display = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        timerElement.textContent = display;
    }
    
    updateTimer();
    setInterval(updateTimer, 1000);
}

document.addEventListener('DOMContentLoaded', function() {
    startDashboardTimer();
});
@endif
</script>
@endsection