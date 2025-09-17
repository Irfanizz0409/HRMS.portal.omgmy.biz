@extends('layouts.sidebar')

@section('page-title', 'My Attendance History')

@section('content')
<div class="space-y-6">
    <!-- Header & Quick Action -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">My Attendance History</h2>
                <p class="text-gray-600 dark:text-gray-400">Track your attendance records and working hours</p>
            </div>
            <a href="{{ route('attendance.clock') }}" 
               class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-2 px-4 rounded-lg transition-all transform hover:scale-105">
                Clock In/Out
            </a>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filter by Date Range</h3>
        
        <form method="GET" action="{{ route('attendance.history') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                <input type="date" name="start_date" id="start_date" 
                       value="{{ request('start_date', $startDate) }}"
                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                <input type="date" name="end_date" id="end_date" 
                       value="{{ request('end_date', $endDate) }}"
                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    Filter
                </button>
                <a href="{{ route('attendance.history') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @php
            $userSettings = \App\Models\UserWorkSetting::where('user_id', auth()->id())->first();
            $requiredHours = $userSettings ? $userSettings->required_hours_per_day : 8.00;
            $deadline = $userSettings ? date('g:i A', strtotime($userSettings->clock_in_deadline)) : '9:45 AM';
        @endphp
        
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Total Records</h3>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $attendances->total() }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">Attendance entries</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Complete Days</h3>
            <div class="text-2xl font-bold text-green-600">{{ $attendances->where('total_hours', '>=', $requiredHours)->count() }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $requiredHours }}+ hours worked</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Total Hours</h3>
            <div class="text-2xl font-bold text-blue-600">{{ number_format($attendances->sum('total_hours'), 1) }}h</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">Hours worked</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Late Days</h3>
            <div class="text-2xl font-bold text-yellow-600">{{ $attendances->where('status', 'late')->count() }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">After {{ $deadline }}</div>
        </div>
    </div>

    <!-- Attendance History Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Attendance Records
                </h3>
                
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Showing {{ $attendances->count() }} of {{ $attendances->total() }} records
                </div>
            </div>
        </div>

        @if($attendances->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Clock In
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Clock Out
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Working Hours
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach($attendances as $attendance)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $attendance->date->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $attendance->date->format('l') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $attendance->formatted_clock_in ?? '-' }}
                                    </div>
                                    @if($attendance->hasCheckedIn())
                                        @php
                                            $userSettings = \App\Models\UserWorkSetting::where('user_id', auth()->id())->first();
                                            $deadline = $userSettings ? $userSettings->clock_in_deadline : '09:45:00';
                                            $clockInTime = \Carbon\Carbon::createFromFormat('H:i:s', $attendance->clock_in_time);
                                            $deadlineTime = \Carbon\Carbon::createFromFormat('H:i:s', $deadline);
                                            $isLate = $clockInTime->gt($deadlineTime);
                                            $deadlineDisplay = date('g:i A', strtotime($deadline));
                                        @endphp
                                        
                                        @if($isLate)
                                            <div class="text-xs text-red-600 font-medium">Late (After {{ $deadlineDisplay }})</div>
                                        @else
                                            <div class="text-xs text-green-600 font-medium">On Time</div>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $attendance->formatted_clock_out ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($attendance->hasCheckedIn() && !$attendance->hasCheckedOut() && $attendance->date->isToday())
                                        <!-- Live Timer for Today's Active Session -->
                                        <div class="live-timer text-green-600 font-bold" 
                                             data-clock-in="{{ $attendance->date->format('Y-m-d') }} {{ $attendance->clock_in_time }}">
                                            00:00:00
                                        </div>
                                        <div class="text-xs text-green-600">Currently working</div>
                                    @else
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $attendance->total_hours ? number_format($attendance->total_hours, 2) . ' hours' : '-' }}
                                        </div>
                                        @php
                                            $userSettings = \App\Models\UserWorkSetting::where('user_id', auth()->id())->first();
                                            $requiredHours = $userSettings ? $userSettings->required_hours_per_day : 8.00;
                                        @endphp
                                        @if($attendance->total_hours >= $requiredHours)
                                            <div class="text-xs text-green-600">Complete Day ({{ $requiredHours }}+ hours)</div>
                                        @elseif($attendance->total_hours > 0)
                                            <div class="text-xs text-orange-600">{{ number_format($requiredHours - $attendance->total_hours, 1) }}h remaining</div>
                                        @endif
                                        @if($attendance->getOvertimeHours() > 0)
                                            <div class="text-xs text-blue-600">
                                                +{{ $attendance->getOvertimeHours() }}h Overtime
                                            </div>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($attendance->status == 'safe') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                        @elseif($attendance->status == 'late') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                        @elseif($attendance->status == 'complete') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                        @elseif($attendance->status == 'late_complete') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                        @elseif($attendance->status == 'early_complete') bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-300
                                        @elseif($attendance->status == 'late_early_complete') bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300
                                        @elseif($attendance->status == 'incomplete') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300
                                        @elseif($attendance->status == 'cross_day') bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-300
                                        @elseif($attendance->status == 'absent') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    @if($attendance->clock_in_photo || $attendance->clock_out_photo)
                                        <button class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" 
                                                onclick="viewPhotos('{{ $attendance->id }}')">
                                            View Photos
                                        </button>
                                    @endif
                                    @if($attendance->notes)
                                        <button class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300" 
                                                onclick="viewNotes('{{ $attendance->notes }}')">
                                            Notes
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $attendances->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-500 dark:text-gray-400 text-lg mb-4">
                    No attendance records found for the selected period.
                </div>
                <a href="{{ route('attendance.clock') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
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
        <a href="{{ route('attendance.index') }}" 
           class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-bold py-3 px-8 rounded-lg inline-block shadow-lg transition-all duration-300 transform hover:scale-105">
            Dashboard
        </a>
    </div>
</div>

<script>
    // Live timer for today's active session only
    function initializeLiveTimers() {
        const timers = document.querySelectorAll('.live-timer');
        
        timers.forEach(timer => {
            const clockInTime = new Date(timer.dataset.clockIn);
            
            function updateTimer() {
                const now = new Date();
                const diff = now - clockInTime;
                
                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                
                const display = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                timer.textContent = display;
            }
            
            updateTimer();
            setInterval(updateTimer, 1000);
        });
    }

    // Initialize timers when page loads
    document.addEventListener('DOMContentLoaded', function() {
        initializeLiveTimers();
    });

    function viewPhotos(attendanceId) {
        fetch(`/attendance/${attendanceId}/photos`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let photoHtml = '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
                    
                    if (data.clock_in_photo) {
                        photoHtml += `
                            <div class="text-center">
                                <h4 class="font-bold mb-2 text-gray-900 dark:text-white">Clock In Photo</h4>
                                <img src="/storage/${data.clock_in_photo}" alt="Clock In" class="w-full h-48 object-cover rounded border">
                            </div>
                        `;
                    }
                    
                    if (data.clock_out_photo) {
                        photoHtml += `
                            <div class="text-center">
                                <h4 class="font-bold mb-2 text-gray-900 dark:text-white">Clock Out Photo</h4>
                                <img src="/storage/${data.clock_out_photo}" alt="Clock Out" class="w-full h-48 object-cover rounded border">
                            </div>
                        `;
                    }
                    
                    photoHtml += '</div>';
                    
                    const modal = document.createElement('div');
                    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                    modal.innerHTML = `
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-4xl mx-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">My Attendance Photos</h3>
                                <button onclick="this.closest('.fixed').remove()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 text-2xl">&times;</button>
                            </div>
                            ${photoHtml}
                        </div>
                    `;
                    document.body.appendChild(modal);
                } else {
                    alert('No photos found for this attendance record.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading photos');
            });
    }

    function viewNotes(notes) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        modal.innerHTML = `
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-md mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Notes</h3>
                    <button onclick="this.closest('.fixed').remove()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 text-2xl">&times;</button>
                </div>
                <p class="text-gray-700 dark:text-gray-300">${notes}</p>
            </div>
        `;
        document.body.appendChild(modal);
    }
</script>
@endsection