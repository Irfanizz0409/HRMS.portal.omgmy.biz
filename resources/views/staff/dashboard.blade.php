<!-- resources/views/staff/dashboard.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Staff Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-200">
                <div class="p-8 text-gray-900">
                    <h3 class="text-3xl font-bold mb-3 text-gray-800">Welcome, {{ Auth::user()->name }}!</h3>
                    <p class="mb-10 text-gray-600 text-lg">Staff Dashboard - You can clock in/out and view your attendance.</p>
                    
                    <!-- Quick Actions Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Clock In/Out -->
                        <div class="bg-white border-2 border-blue-900 p-8 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                            <div class="flex items-center mb-6">
                                <div class="bg-blue-900 p-4 rounded-xl mr-5">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-2xl font-bold text-blue-900">Clock In/Out</h4>
                            </div>
                            <p class="text-gray-600 text-base mb-8 leading-relaxed">Mark your attendance with photo verification</p>
                            <a href="{{ route('attendance.clock') }}" 
                               class="inline-block bg-blue-900 text-white font-bold py-4 px-8 rounded-lg hover:bg-blue-800 transition-colors duration-200 shadow-md hover:shadow-lg text-lg">
                                Clock In/Out Now
                            </a>
                        </div>

                        <!-- My Profile -->
                        <div class="bg-white border-2 border-gray-400 p-8 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                            <div class="flex items-center mb-6">
                                <div class="bg-gray-600 p-4 rounded-xl mr-5">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-2xl font-bold text-gray-700">My Profile</h4>
                            </div>
                            <p class="text-gray-600 text-base mb-8 leading-relaxed">View and update your profile information</p>
                            <a href="{{ route('my-profile') }}" 
                               class="inline-block bg-gray-600 text-white font-bold py-4 px-8 rounded-lg hover:bg-gray-700 transition-colors duration-200 shadow-md hover:shadow-lg text-lg">
                                View Profile
                            </a>
                        </div>

                        <!-- My Attendance -->
                        <div class="bg-white border-2 border-blue-900 p-8 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                            <div class="flex items-center mb-6">
                                <div class="bg-blue-900 p-4 rounded-xl mr-5">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-2xl font-bold text-blue-900">My Attendance</h4>
                            </div>
                            <p class="text-gray-600 text-base mb-8 leading-relaxed">View your attendance history and records</p>
                            <a href="{{ route('attendance.history') }}" 
                               class="inline-block bg-blue-900 text-white font-bold py-4 px-8 rounded-lg hover:bg-blue-800 transition-colors duration-200 shadow-md hover:shadow-lg text-lg">
                                View History
                            </a>
                        </div>

                        <!-- Leave Requests -->
                        <div class="bg-white border-2 border-gray-300 p-8 rounded-xl shadow-lg">
                            <div class="flex items-center mb-6">
                                <div class="bg-gray-400 p-4 rounded-xl mr-5">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-2xl font-bold text-gray-500">Leave Requests</h4>
                            </div>
                            <p class="text-gray-500 text-base mb-8 leading-relaxed">Apply for leave and view request status</p>
                            <button class="inline-block bg-gray-400 text-white font-bold py-4 px-8 rounded-lg cursor-not-allowed text-lg">
                                Coming Soon
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>