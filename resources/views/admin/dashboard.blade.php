@extends('layouts.sidebar')

@section('page-title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Welcome, {{ Auth::user()->name }}!</h2>
        <p class="text-gray-600 dark:text-gray-400">Admin Dashboard - Manage the entire HR system</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Employees</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ \App\Models\User::count() }}</p>
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
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Today</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ \App\Models\Attendance::whereDate('date', today())->whereNotNull('clock_in_time')->count() }}</p>
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
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Late Today</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ \App\Models\Attendance::whereDate('date', today())->where('status', 'late')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Incomplete</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ \App\Models\Attendance::whereDate('date', today())->where('status', 'incomplete')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Employee Management</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Manage employee profiles and access</p>
            <a href="{{ route('employees.index') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                Manage Employees
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Attendance Reports</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">View employee attendance and reports</p>
            <a href="{{ route('attendance.admin') }}" 
               class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                View Reports
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Work Settings</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Configure employee work hours and settings</p>
            <a href="{{ route('admin.work-settings.index') }}" 
               class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                Manage Settings
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">System Settings</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Configure system settings and preferences</p>
            <button class="bg-gray-400 text-white font-medium py-2 px-4 rounded-lg cursor-not-allowed" disabled>
                Coming Soon
            </button>
        </div>
    </div>

    <!-- Today's Attendance Overview -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Today's Attendance Overview</h3>
            <a href="{{ route('attendance.admin') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                View All →
            </a>
        </div>

        @php
            $todaysAttendance = \App\Models\Attendance::with('user')
                ->whereDate('date', today())
                ->orderBy('clock_in_time', 'desc')
                ->limit(5)
                ->get();
        @endphp

        @if($todaysAttendance->count() > 0)
            <div class="space-y-3">
                @foreach($todaysAttendance as $attendance)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center mr-3">
                                <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                    {{ substr($attendance->user->name, 0, 2) }}
                                </span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $attendance->user->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $attendance->user->employee_id }}</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $attendance->formatted_clock_in ?? '-' }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Clock In</div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $attendance->formatted_clock_out ?? '-' }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Clock Out</div>
                            </div>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($attendance->status == 'safe') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                @elseif($attendance->status == 'late') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                @elseif($attendance->status == 'complete') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                @elseif($attendance->status == 'incomplete') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300
                                @elseif($attendance->status == 'cross_day') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="text-gray-500 dark:text-gray-400">No attendance records for today yet.</div>
            </div>
        @endif
    </div>
</div>
@endsection