<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Welcome, {{ Auth::user()->name }}!</h3>
                    <p class="mb-6">Admin Dashboard - You have full access to manage the entire system.</p>
                    
                    <!-- Quick Actions Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Employee Management -->
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-blue-800 mb-2">Employee Management</h4>
                            <p class="text-blue-600 text-sm mb-4">Manage all employees, profiles, and access</p>
                            <a href="{{ route('employees.index') }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Manage Employees
                            </a>
                        </div>

                        <!-- Attendance Management -->
                        <div class="bg-orange-50 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-orange-800 mb-2">Attendance Reports</h4>
                            <p class="text-orange-600 text-sm mb-4">View all employee attendance and manage records</p>
                            <a href="{{ route('attendance.admin') }}" 
                               class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                                View Reports
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

                        <!-- Department Management -->
                        <div class="bg-purple-50 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-purple-800 mb-2">Department Management</h4>
                            <p class="text-purple-600 text-sm mb-4">Create and manage company departments</p>
                            <button class="bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed" disabled>
                                Coming Soon
                            </button>
                        </div>

                        <!-- Leave Management -->
                        <div class="bg-pink-50 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-pink-800 mb-2">Leave Management</h4>
                            <p class="text-pink-600 text-sm mb-4">Approve leave requests and manage policies</p>
                            <button class="bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed" disabled>
                                Coming Soon
                            </button>
                        </div>

                        <!-- System Settings -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">System Settings</h4>
                            <p class="text-gray-600 text-sm mb-4">Configure system settings and preferences</p>
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