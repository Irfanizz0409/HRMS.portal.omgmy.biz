@extends('layouts.sidebar')

@section('page-title', 'Employee Work Settings')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Employee Work Settings</h2>
                <p class="text-gray-600 dark:text-gray-400">Configure individual work hours and requirements for each employee</p>
            </div>
            <button onclick="showBulkUpdateModal()" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                Bulk Update Settings
            </button>
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
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filter Employees</h3>
        
        <form method="GET" action="{{ route('admin.work-settings.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role</label>
                <select name="role" id="role" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $role)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department</label>
                <select name="department" id="department" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Departments</option>
                    @foreach($departments as $department)
                        <option value="{{ $department }}" {{ request('department') == $department ? 'selected' : '' }}>
                            {{ $department }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin.work-settings.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Employee Work Settings Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                Employee Work Settings ({{ $users->total() }} employees)
            </h3>
        </div>

        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="select-all" class="rounded border-gray-300 dark:border-gray-600">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Employee
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Required Hours
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Clock-in Deadline
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Work Schedule
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
                        @foreach($users as $user)
                            @php
                                $workSetting = $user->workSetting;
                                $hasCustomSettings = $workSetting && (
                                    $workSetting->required_hours_per_day != 8.00 ||
                                    $workSetting->clock_in_deadline != '09:45:00' ||
                                    $workSetting->flexible_timing
                                );
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="user-checkbox rounded border-gray-300 dark:border-gray-600">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                                    {{ substr($user->name, 0, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->employee_id }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $workSetting ? $workSetting->required_hours_per_day : '8.00' }} hours/day
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $workSetting ? date('g:i A', strtotime($workSetting->clock_in_deadline)) : '9:45 AM' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    @if($workSetting)
                                        {{ date('g:i A', strtotime($workSetting->preferred_start_time)) }} - 
                                        {{ date('g:i A', strtotime($workSetting->preferred_end_time)) }}
                                    @else
                                        10:00 AM - 6:00 PM
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($hasCustomSettings)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                            Custom
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            Default
                                        </span>
                                    @endif
                                    @if($workSetting && $workSetting->flexible_timing)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 ml-1">
                                            Flexible
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.work-settings.edit', $user) }}" 
                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        Edit
                                    </a>
                                    @if($hasCustomSettings)
                                        <form method="POST" action="{{ route('admin.work-settings.reset', $user) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="text-orange-600 hover:text-orange-900 dark:text-orange-400 dark:hover:text-orange-300"
                                                    onclick="return confirm('Reset work settings to default for {{ $user->name }}?')">
                                                Reset
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $users->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-500 dark:text-gray-400 text-lg mb-4">
                    No employees found matching the selected criteria.
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Bulk Update Modal -->
<div id="bulk-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-md mx-4 w-full">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Bulk Update Work Settings</h3>
            <button onclick="hideBulkUpdateModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="bulk-form" method="POST" action="{{ route('admin.work-settings.bulk-update') }}">
            @csrf
            <div id="selected-users-container"></div>
            
            <div class="space-y-4">
                <div>
                    <label for="bulk-required-hours" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Required Hours per Day</label>
                    <input type="number" id="bulk-required-hours" name="required_hours_per_day" step="0.5" min="1" max="24" value="8.00"
                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="bulk-start-time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Time</label>
                        <input type="time" id="bulk-start-time" name="preferred_start_time" value="10:00"
                               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="bulk-end-time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Time</label>
                        <input type="time" id="bulk-end-time" name="preferred_end_time" value="18:00"
                               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <div>
                    <label for="bulk-deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Clock-in Deadline</label>
                    <input type="time" id="bulk-deadline" name="clock_in_deadline" value="09:45"
                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="bulk-overtime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Overtime Threshold</label>
                    <input type="number" id="bulk-overtime" name="overtime_threshold" step="0.5" min="0" max="24" value="8.00"
                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="flexible_timing" class="rounded border-gray-300 dark:border-gray-600">
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Flexible Timing</span>
                    </label>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="hideBulkUpdateModal()" 
                        class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Update Selected
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Select all functionality
    document.getElementById('select-all').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    function showBulkUpdateModal() {
        const selectedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
        
        if (selectedCheckboxes.length === 0) {
            alert('Please select at least one employee to update.');
            return;
        }
        
        // Add hidden inputs for selected users
        const container = document.getElementById('selected-users-container');
        container.innerHTML = '';
        
        selectedCheckboxes.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'user_ids[]';
            input.value = checkbox.value;
            container.appendChild(input);
        });
        
        document.getElementById('bulk-modal').classList.remove('hidden');
        document.getElementById('bulk-modal').classList.add('flex');
    }

    function hideBulkUpdateModal() {
        document.getElementById('bulk-modal').classList.add('hidden');
        document.getElementById('bulk-modal').classList.remove('flex');
    }
</script>
@endsection