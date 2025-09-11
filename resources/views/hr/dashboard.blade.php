<!-- resources/views/hr/dashboard.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('HR Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Welcome, {{ Auth::user()->name }}!</h3>
                    <p class="mb-6">HR Dashboard - You can manage employees and HR functions.</p>
                    
                    <!-- Quick Actions Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Employee Management -->
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-blue-800 mb-2">Employee Management</h4>
                            <p class="text-blue-600 text-sm mb-4">Manage employee profiles and access</p>
                            <a href="{{ route('employees.index') }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Manage Employees
                            </a>
                        </div>

                        <!-- My Profile -->
                        <div class="bg-green-50 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-green-800 mb-2">My Profile</h4>
                            <p class="text-green-600 text-sm mb-4">View and update your profile information</p>
                            <a href="{{ route('my-profile') }}" 
                               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                View Profile
                            </a>
                        </div>

                        <!-- Attendance Reports -->
                        <div class="bg-yellow-50 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-yellow-800 mb-2">Attendance Reports</h4>
                            <p class="text-yellow-600 text-sm mb-4">View employee attendance and reports</p>
                            <button class="bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed" disabled>
                                Coming Soon
                            </button>
                        </div>

                        <!-- Leave Management -->
                        <div class="bg-purple-50 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-purple-800 mb-2">Leave Management</h4>
                            <p class="text-purple-600 text-sm mb-4">Approve and manage leave requests</p>
                            <button class="bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed" disabled>
                                Coming Soon
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>