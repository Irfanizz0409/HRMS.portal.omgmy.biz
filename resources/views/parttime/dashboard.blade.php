@extends('layouts.sidebar')

@section('page-title', 'Part-time Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Welcome, {{ Auth::user()->name }}!</h2>
        <p class="text-gray-600">Part-time Dashboard - Manage your flexible work schedule and hours</p>
    </div>

    <!-- Part-time Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Hours This Week</p>
                    <p class="text-2xl font-semibold text-gray-900">18/20</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">This Month Earnings</p>
                    <p class="text-2xl font-semibold text-gray-900">RM 1,440</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Scheduled Days</p>
                    <p class="text-2xl font-semibold text-gray-900">3/week</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Overtime Hours</p>
                    <p class="text-2xl font-semibold text-gray-900">2h</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Clock In/Out Card -->
        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-semibold mb-2">Flexible Hours</h3>
                    <p class="text-indigo-100 mb-4">Track your part-time work schedule</p>
                    <div class="text-sm text-indigo-100">
                        <p>Status: <span class="font-semibold">Not Clocked In</span></p>
                        <p>Today: <span class="font-semibold">0h 0m</span></p>
                        <p>Rate: <span class="font-semibold">RM 20/hour</span></p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="bg-white/20 p-4 rounded-lg mb-4">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                        </svg>
                    </div>
                    <button class="bg-white text-indigo-600 hover:bg-indigo-50 font-semibold py-2 px-4 rounded-lg transition-colors">
                        Clock In/Out
                    </button>
                </div>
            </div>
        </div>

        <!-- Weekly Schedule -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">This Week's Schedule</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">Monday</p>
                        <p class="text-sm text-gray-600">2:00 PM - 6:00 PM</p>
                    </div>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">Completed</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">Wednesday</p>
                        <p class="text-sm text-gray-600">10:00 AM - 3:00 PM</p>
                    </div>
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">Today</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">Friday</p>
                        <p class="text-sm text-gray-600">1:00 PM - 5:00 PM</p>
                    </div>
                    <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded">Scheduled</span>
                </div>
            </div>
        </div>

        <!-- Earnings Summary -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Earnings Summary</h3>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Regular Hours (72h)</span>
                    <span class="font-semibold text-gray-900">RM 1,440</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Overtime (2h)</span>
                    <span class="font-semibold text-gray-900">RM 60</span>
                </div>
                <hr class="my-2">
                <div class="flex justify-between text-lg font-semibold">
                    <span class="text-gray-900">Total</span>
                    <span class="text-green-600">RM 1,500</span>
                </div>
            </div>
            <button class="w-full mt-4 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg">
                View Detailed Report
            </button>
        </div>

        <!-- My Profile -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">My Profile</h3>
            <p class="text-gray-600 mb-4">Update your availability and preferences</p>
            <a href="{{ route('employees.show', auth()->user()) }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg">
                View Profile
            </a>
        </div>
    </div>

    <!-- Recent Activities & Quick Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Work Sessions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Work Sessions</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">Monday, Sep 9</p>
                        <p class="text-sm text-gray-600">2:00 PM - 6:15 PM</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">4h 15m</p>
                        <p class="text-sm text-green-600">+15m overtime</p>
                    </div>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900">Friday, Sep 6</p>
                        <p class="text-sm text-gray-600">1:00 PM - 5:00 PM</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">4h 0m</p>
                        <p class="text-sm text-gray-600">Regular</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Goals -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Goals</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Hours Target</span>
                        <span class="text-gray-900">72/80 hours</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 90%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Earnings Target</span>
                        <span class="text-gray-900">RM 1,500/1,600</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: 94%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">Attendance Rate</span>
                        <span class="text-gray-900">95%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-600 h-2 rounded-full" style="width: 95%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection