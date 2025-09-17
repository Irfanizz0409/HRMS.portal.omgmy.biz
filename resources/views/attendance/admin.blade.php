@extends('layouts.sidebar')

@section('page-title', 'Employee Attendance Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Employee Attendance Management</h2>
                <p class="text-gray-600 dark:text-gray-400">Monitor and manage all employee attendance records</p>
            </div>
            <div class="space-x-3">
                <button class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors" disabled>
                    Export Excel
                </button>
                <a href="{{ route('employees.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Manage Employees
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded dark:bg-green-900 dark:text-green-300">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filter Attendance Records</h3>
        
        <form method="GET" action="{{ route('attendance.admin') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date</label>
                <input type="date" name="date" id="date" 
                       value="{{ request('date') }}"
                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label for="employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Employee</label>
                <select name="employee_id" id="employee_id" 
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Employees</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" 
                                {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->name }} ({{ $employee->employee_id }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                <select name="status" id="status" 
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="safe" {{ request('status') == 'safe' ? 'selected' : '' }}>Safe</option>
                    <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                    <option value="complete" {{ request('status') == 'complete' ? 'selected' : '' }}>Complete</option>
                    <option value="late_complete" {{ request('status') == 'late_complete' ? 'selected' : '' }}>Late Complete</option>
                    <option value="early_complete" {{ request('status') == 'early_complete' ? 'selected' : '' }}>Early Complete</option>
                    <option value="incomplete" {{ request('status') == 'incomplete' ? 'selected' : '' }}>Incomplete</option>
                    <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    Filter
                </button>
                <a href="{{ route('attendance.admin') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Total Records</h3>
                    <div class="text-3xl font-bold text-blue-600">{{ $attendances->count() }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $selectedDate ? 'Selected date' : 'All records' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Safe/Complete</h3>
                    <div class="text-3xl font-bold text-green-600">
                        {{ $attendances->whereIn('status', ['safe', 'complete', 'early_complete'])->count() }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">On time arrivals</div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Late</h3>
                    <div class="text-3xl font-bold text-yellow-600">
                        {{ $attendances->whereIn('status', ['late', 'late_complete', 'late_early_complete'])->count() }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">After deadline</div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 dark:bg-orange-900 text-orange-600 dark:text-orange-400">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Incomplete</h3>
                    <div class="text-3xl font-bold text-orange-600">
                        {{ $attendances->whereIn('status', ['incomplete', 'absent'])->count() }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Not finished</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Records Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                Employee Attendance Records
                @if($selectedDate)
                    - {{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}
                @else
                    - All Dates
                @endif
            </h3>
        </div>

        @if($attendances->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Employee
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Clock In
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Clock Out
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Date
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
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                                    {{ substr($attendance->user->name, 0, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $attendance->user->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $attendance->user->employee_id }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $attendance->user->department ?? 'No Department' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $attendance->formatted_clock_in ?? '-' }}
                                    </div>
                                    @if($attendance->hasCheckedIn())
                                        @php
                                            $userSettings = \App\Models\UserWorkSetting::where('user_id', $attendance->user_id)->first();
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
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $attendance->date->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $attendance->date->format('l') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        @if($attendance->hasCheckedIn() && !$attendance->hasCheckedOut())
                                            <!-- Live Timer for Active Sessions -->
                                            <div class="live-timer text-green-600 font-bold" 
                                                 data-clock-in="{{ $attendance->date->format('Y-m-d') }} {{ $attendance->clock_in_time }}">
                                                00:00:00
                                            </div>
                                            <div class="text-xs text-green-600">Currently working</div>
                                        @else
                                            {{ $attendance->working_hours_display ?? '-' }}
                                        @endif
                                    </div>
                                    
                                    @php
                                        $userSettings = \App\Models\UserWorkSetting::where('user_id', $attendance->user_id)->first();
                                        $requiredHours = $userSettings ? $userSettings->required_hours_per_day : 8.00;
                                    @endphp
                                    
                                    @if($attendance->total_hours >= $requiredHours)
                                        <div class="text-xs text-green-600">Complete Day ({{ $requiredHours }}+ hours)</div>
                                    @elseif($attendance->total_hours > 0)
                                        <div class="text-xs text-orange-600">{{ number_format($requiredHours - $attendance->total_hours, 1) }}h remaining</div>
                                    @endif
                                    
                                    @if($attendance->getOvertimeHours() > 0)
                                        <div class="text-xs text-blue-600">
                                            +{{ $attendance->getOvertimeHours() }}h OT
                                        </div>
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
                                            Photos
                                        </button>
                                    @endif
                                    
                                    <button class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" 
                                            onclick="editAttendance('{{ $attendance->id }}', '{{ $attendance->clock_in_time }}', '{{ $attendance->clock_out_time }}', '{{ $attendance->status }}', '{{ $attendance->notes ?? '' }}')">
                                        Edit
                                    </button>
                                    
                                    <form method="POST" action="{{ route('attendance.destroy', $attendance) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                onclick="return confirm('Are you sure you want to delete this attendance record? This action cannot be undone.')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-500 dark:text-gray-400 text-lg mb-4">
                    No attendance records found for the selected criteria.
                </div>
                <div class="text-sm text-gray-400 dark:text-gray-500">
                    Employee attendance will appear here once they clock in.
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Edit Attendance Modal -->
<div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-md mx-4 w-full">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit Attendance Record</h3>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="edit-form" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label for="edit-clock-in" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Clock In Time</label>
                    <input type="time" id="edit-clock-in" name="clock_in_time" 
                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="edit-clock-out" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Clock Out Time</label>
                    <input type="time" id="edit-clock-out" name="clock_out_time" 
                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="edit-status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select id="edit-status" name="status" 
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="safe">Safe</option>
                        <option value="late">Late</option>
                        <option value="complete">Complete</option>
                        <option value="late_complete">Late Complete</option>
                        <option value="early_complete">Early Complete</option>
                        <option value="late_early_complete">Late Early Complete</option>
                        <option value="incomplete">Incomplete</option>
                        <option value="absent">Absent</option>
                    </select>
                </div>
                
                <div>
                    <label for="edit-notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                    <textarea id="edit-notes" name="notes" rows="3" 
                              class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Add any notes about this attendance record..."></textarea>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeEditModal()" 
                        class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Update Record
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Live timers for all active work sessions
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

    function editAttendance(id, clockIn, clockOut, status, notes) {
        document.getElementById('edit-form').action = `/attendance/${id}`;
        document.getElementById('edit-clock-in').value = clockIn || '';
        document.getElementById('edit-clock-out').value = clockOut || '';
        document.getElementById('edit-status').value = status;
        document.getElementById('edit-notes').value = notes || '';
        document.getElementById('edit-modal').classList.remove('hidden');
        document.getElementById('edit-modal').classList.add('flex');
    }

    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
        document.getElementById('edit-modal').classList.remove('flex');
    }

    function viewPhotos(attendanceId) {
    const baseUrl = '{{ url('/') }}'; // This will output the correct Laravel base URL
    fetch(`${baseUrl}/attendance/${attendanceId}/photos`)
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Received data:', data);
            
            if (data.success) {
                let photoHtml = '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
                
                if (data.clock_in_photo) {
                    photoHtml += `
                        <div class="text-center">
                            <h4 class="font-bold mb-2 text-gray-900 dark:text-white">Clock In Photo</h4>
                            <img src="${baseUrl}/storage/${data.clock_in_photo}" alt="Clock In" class="w-full h-48 object-cover rounded border">
                        </div>
                    `;
                }
                
                if (data.clock_out_photo) {
                    photoHtml += `
                        <div class="text-center">
                            <h4 class="font-bold mb-2 text-gray-900 dark:text-white">Clock Out Photo</h4>
                            <img src="${baseUrl}/storage/${data.clock_out_photo}" alt="Clock Out" class="w-full h-48 object-cover rounded border">
                        </div>
                    `;
                }
                
                photoHtml += '</div>';
                
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                modal.innerHTML = `
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-4xl mx-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Attendance Photos</h3>
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
            console.error('Error details:', error);
            alert('Error loading photos: ' + error.message);
        });
}
</script>
@endsection