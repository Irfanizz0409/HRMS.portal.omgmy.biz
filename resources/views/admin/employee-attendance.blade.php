@extends('layouts.sidebar')

@section('page-title', $employee->name . ' - Attendance Records')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <div class="h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center mr-4">
                    <span class="text-lg font-medium text-blue-600 dark:text-blue-400">
                        {{ substr($employee->name, 0, 2) }}
                    </span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $employee->name }}</h2>
                    <p class="text-gray-600 dark:text-gray-400">{{ $employee->employee_id }} • {{ ucfirst(str_replace('_', ' ', $employee->role)) }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-500">{{ $employee->department ?? 'No Department' }} • {{ $employee->position ?? 'No Position' }}</p>
                </div>
            </div>
            <a href="{{ route('employees.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                Back to Employees
            </a>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filter by Date Range</h3>
        
        <form method="GET" action="{{ route('employees.attendance', $employee) }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                <a href="{{ route('employees.attendance', $employee) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Attendance Records Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                Attendance Records ({{ $attendances->total() }} total)
            </h3>
        </div>

        @if($attendances->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Clock In</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Clock Out</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Working Hours</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
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
                                    @if($attendance->hasCheckedIn() && $attendance->isLate())
                                        <div class="text-xs text-red-600">Late</div>
                                    @elseif($attendance->hasCheckedIn())
                                        <div class="text-xs text-green-600">On Time</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    {{ $attendance->formatted_clock_out ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $attendance->total_hours ? number_format($attendance->total_hours, 2) . ' hours' : '-' }}
                                    </div>
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
                                        @elseif($attendance->status == 'incomplete') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300
                                        @elseif($attendance->status == 'cross_day') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($attendance->clock_in_photo || $attendance->clock_out_photo)
                                        <button class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300" 
                                                onclick="viewPhotos('{{ $attendance->id }}')">
                                            View Photos
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
                {{ $attendances->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-500 dark:text-gray-400 text-lg mb-4">
                    No attendance records found for {{ $employee->name }}.
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function viewPhotos(attendanceId) {
        // Same photo viewing script as in other attendance views
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
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">${'{{ $employee->name }}'} Attendance Photos</h3>
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
</script>
@endsection