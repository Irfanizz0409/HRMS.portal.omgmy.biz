@extends('layouts.sidebar')

@section('page-title', 'Employee Management')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Employee Management</h2>
                <p class="text-gray-600">Manage all employees and their information</p>
            </div>
            <a href="{{ route('employees.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add Employee
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" action="{{ route('employees.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Name, ID, Email..." 
                           class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Department Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                    <select name="department" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department }}" {{ request('department') == $department ? 'selected' : '' }}>
                                {{ $department }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Role Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select name="role" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $role)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                    </select>
                </div>
            </div>

            <div class="flex space-x-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                    Apply Filters
                </button>
                <a href="{{ route('employees.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Employee Cards Grid -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                Employees ({{ $employees->total() }} total)
            </h3>
        </div>

        @if($employees->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                @foreach($employees as $employee)
                    <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                        <!-- Avatar and Basic Info -->
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-lg font-semibold text-blue-600">
                                    {{ substr($employee->name, 0, 2) }}
                                </span>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-lg font-semibold text-gray-900">{{ $employee->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $employee->employee_id }}</p>
                            </div>
                        </div>

                        <!-- Employee Details -->
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Email:</span>
                                <span class="text-sm text-gray-900">{{ $employee->email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Role:</span>
                                <span class="text-sm text-gray-900 capitalize">{{ str_replace('_', ' ', $employee->role) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Department:</span>
                                <span class="text-sm text-gray-900">{{ $employee->department ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Status:</span>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($employee->employment_status == 'active') bg-green-100 text-green-800
                                    @elseif($employee->employment_status == 'inactive') bg-yellow-100 text-yellow-800
                                    @elseif($employee->employment_status == 'blocked') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($employee->employment_status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
<div class="flex space-x-1">
    <a href="{{ route('employees.show', $employee) }}" 
       class="flex-1 bg-blue-50 text-blue-600 hover:bg-blue-100 text-center py-1 px-2 rounded text-xs font-medium">
        View
    </a>
    <a href="{{ route('employees.edit', $employee) }}" 
       class="flex-1 bg-green-50 text-green-600 hover:bg-green-100 text-center py-1 px-2 rounded text-xs font-medium">
        Edit
    </a>
    <a href="{{ route('employees.attendance', $employee) }}" 
       class="flex-1 bg-purple-50 text-purple-600 hover:bg-purple-100 text-center py-1 px-2 rounded text-xs font-medium">
        Attendance
    </a>
                            @if($employee->employment_status == 'active')
                                <form action="{{ route('employees.toggle-status', $employee) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full bg-red-50 text-red-600 hover:bg-red-100 py-2 px-3 rounded text-sm font-medium">
                                        Block
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('employees.toggle-status', $employee) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full bg-green-50 text-green-600 hover:bg-green-100 py-2 px-3 rounded text-sm font-medium">
                                        Activate
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $employees->withQueryString()->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No employees found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by adding a new employee.</p>
                <div class="mt-6">
                    <a href="{{ route('employees.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Add Employee
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection 