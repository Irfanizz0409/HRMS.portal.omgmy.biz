@extends('layouts.sidebar')

@section('page-title', 'Edit Work Settings - ' . $user->name)

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Work Settings</h2>
                <p class="text-gray-600 dark:text-gray-400">Configure work hours and requirements for {{ $user->name }}</p>
            </div>
            <a href="{{ route('admin.work-settings.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                Back to Settings
            </a>
        </div>
    </div>

    <!-- Employee Info Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-16 w-16">
                <div class="h-16 w-16 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                    <span class="text-xl font-medium text-blue-600 dark:text-blue-400">
                        {{ substr($user->name, 0, 2) }}
                    </span>
                </div>
            </div>
            <div class="ml-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                <p class="text-gray-600 dark:text-gray-400">{{ $user->employee_id }} • {{ ucfirst(str_replace('_', ' ', $user->role)) }}</p>
                <p class="text-gray-500 dark:text-gray-500">{{ $user->department ?? 'No Department' }} • {{ $user->position ?? 'No Position' }}</p>
            </div>
        </div>
    </div>

    <!-- Work Settings Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Work Schedule Configuration</h3>
        
        <form method="POST" action="{{ route('admin.work-settings.update', $user) }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Required Hours -->
                <div class="md:col-span-2">
                    <label for="required_hours_per_day" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Required Hours per Day
                    </label>
                    <div class="relative">
                        <input type="number" 
                               id="required_hours_per_day" 
                               name="required_hours_per_day" 
                               step="0.5" 
                               min="1" 
                               max="24" 
                               value="{{ old('required_hours_per_day', $workSetting->required_hours_per_day) }}"
                               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <span class="absolute right-3 top-2 text-gray-500 dark:text-gray-400">hours</span>
                    </div>
                    @error('required_hours_per_day')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preferred Start Time -->
                <div>
                    <label for="preferred_start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Preferred Start Time
                    </label>
                    <input type="time" 
                           id="preferred_start_time" 
                           name="preferred_start_time" 
                           value="{{ old('preferred_start_time', $workSetting->preferred_start_time) }}"
                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('preferred_start_time')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preferred End Time -->
                <div>
                    <label for="preferred_end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Preferred End Time
                    </label>
                    <input type="time" 
                           id="preferred_end_time" 
                           name="preferred_end_time" 
                           value="{{ old('preferred_end_time', $workSetting->preferred_end_time) }}"
                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('preferred_end_time')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Clock-in Deadline -->
                <div>
                    <label for="clock_in_deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Clock-in Deadline (Late After)
                    </label>
                    <input type="time" 
                           id="clock_in_deadline" 
                           name="clock_in_deadline" 
                           value="{{ old('clock_in_deadline', $workSetting->clock_in_deadline) }}"
                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @error('clock_in_deadline')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Employee will be marked as "late" if they clock in after this time
                    </p>
                </div>

                <!-- Overtime Threshold -->
                <div>
                    <label for="overtime_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Overtime Threshold
                    </label>
                    <div class="relative">
                        <input type="number" 
                               id="overtime_threshold" 
                               name="overtime_threshold" 
                               step="0.5" 
                               min="0" 
                               max="24" 
                               value="{{ old('overtime_threshold', $workSetting->overtime_threshold) }}"
                               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <span class="absolute right-3 top-2 text-gray-500 dark:text-gray-400">hours</span>
                    </div>
                    @error('overtime_threshold')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Hours beyond this threshold will be counted as overtime
                    </p>
                </div>

                <!-- Flexible Timing -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="flexible_timing" 
                               name="flexible_timing" 
                               value="1"
                               {{ old('flexible_timing', $workSetting->flexible_timing) ? 'checked' : '' }}
                               class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                        <label for="flexible_timing" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Enable Flexible Timing
                        </label>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Allow employee to have flexible work hours within their schedule
                    </p>
                    @error('flexible_timing')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Admin Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Admin Notes
                    </label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="3" 
                              placeholder="Add any special notes or considerations for this employee's work schedule..."
                              class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $workSetting->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex space-x-3">
                    <a href="{{ route('admin.work-settings.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Cancel
                    </a>
                    
                    @if($workSetting->required_hours_per_day != 8.00 || $workSetting->clock_in_deadline != '09:45:00' || $workSetting->flexible_timing)
                        <button type="button" 
                                onclick="resetToDefault()"
                                class="bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Reset to Default
                        </button>
                    @endif
                </div>
                
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                    Save Work Settings
                </button>
            </div>
        </form>
    </div>

    <!-- Current Settings Preview -->
    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Current Settings Summary</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                <div class="text-sm text-gray-500 dark:text-gray-400">Daily Hours</div>
                <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $workSetting->required_hours_per_day }} hours</div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                <div class="text-sm text-gray-500 dark:text-gray-400">Late Deadline</div>
                <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ date('g:i A', strtotime($workSetting->clock_in_deadline)) }}</div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                <div class="text-sm text-gray-500 dark:text-gray-400">Work Schedule</div>
                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ date('g:i A', strtotime($workSetting->preferred_start_time)) }} - {{ date('g:i A', strtotime($workSetting->preferred_end_time)) }}
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg">
                <div class="text-sm text-gray-500 dark:text-gray-400">Timing Type</div>
                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $workSetting->flexible_timing ? 'Flexible' : 'Fixed' }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reset Confirmation Modal -->
<div id="reset-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 max-w-md mx-4 w-full">
        <div class="text-center">
            <div class="w-16 h-16 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Reset to Default Settings</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                This will reset {{ $user->name }}'s work settings to the default values. Are you sure?
            </p>
            <div class="flex space-x-4">
                <button onclick="hideResetModal()" 
                        class="flex-1 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Cancel
                </button>
                <form method="POST" action="{{ route('admin.work-settings.reset', $user) }}" class="flex-1">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="w-full bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Reset Settings
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function resetToDefault() {
        document.getElementById('reset-modal').classList.remove('hidden');
        document.getElementById('reset-modal').classList.add('flex');
    }

    function hideResetModal() {
        document.getElementById('reset-modal').classList.add('hidden');
        document.getElementById('reset-modal').classList.remove('flex');
    }

    // Auto-calculate end time based on start time and required hours
    document.getElementById('preferred_start_time').addEventListener('change', function() {
        const startTime = this.value;
        const requiredHours = parseFloat(document.getElementById('required_hours_per_day').value) || 8;
        
        if (startTime) {
            const [hours, minutes] = startTime.split(':').map(Number);
            const startMinutes = hours * 60 + minutes;
            const endMinutes = startMinutes + (requiredHours * 60);
            
            const endHours = Math.floor(endMinutes / 60) % 24;
            const endMins = endMinutes % 60;
            
            const endTime = `${endHours.toString().padStart(2, '0')}:${endMins.toString().padStart(2, '0')}`;
            document.getElementById('preferred_end_time').value = endTime;
        }
    });
</script>
@endsection