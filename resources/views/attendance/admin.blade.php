<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Employee Attendance Management
            </h2>
            <a href="{{ route('dashboard.admin') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filter Section -->
            <div class="bg-white border border-gray-300 shadow-lg rounded-xl mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-black mb-4">Filter Attendance Records</h3>
                    
                    <form method="GET" action="{{ route('attendance.admin') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="date" class="block text-sm font-medium text-black mb-1">Date</label>
                            <input type="date" name="date" id="date" 
                                   value="{{ request('date', today()->format('Y-m-d')) }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="employee_id" class="block text-sm font-medium text-black mb-1">Employee</label>
                            <select name="employee_id" id="employee_id" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
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
                            <label for="status" class="block text-sm font-medium text-black mb-1">Status</label>
                            <select name="status" id="status" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Status</option>
                                <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                                <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                                <option value="incomplete" {{ request('status') == 'incomplete' ? 'selected' : '' }}>Incomplete</option>
                                <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="bg-blue-900 hover:bg-blue-800 text-white font-bold py-2 px-6 rounded-lg mr-2">
                                Filter
                            </button>
                            <a href="{{ route('attendance.admin') }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white border border-gray-300 shadow-lg rounded-xl p-6">
                    <h3 class="text-lg font-bold text-black mb-2">Total Records</h3>
                    <div class="text-3xl font-bold text-blue-900">{{ $attendances->count() }}</div>
                    <div class="text-sm text-gray-600">Today's attendance</div>
                </div>

                <div class="bg-white border border-gray-300 shadow-lg rounded-xl p-6">
                    <h3 class="text-lg font-bold text-black mb-2">Present</h3>
                    <div class="text-3xl font-bold text-green-600">
                        {{ $attendances->where('status', 'present')->count() }}
                    </div>
                    <div class="text-sm text-gray-600">On time</div>
                </div>

                <div class="bg-white border border-gray-300 shadow-lg rounded-xl p-6">
                    <h3 class="text-lg font-bold text-black mb-2">Late</h3>
                    <div class="text-3xl font-bold text-yellow-600">
                        {{ $attendances->where('status', 'late')->count() }}
                    </div>
                    <div class="text-sm text-gray-600">Late arrivals</div>
                </div>

                <div class="bg-white border border-gray-300 shadow-lg rounded-xl p-6">
                    <h3 class="text-lg font-bold text-black mb-2">Incomplete</h3>
                    <div class="text-3xl font-bold text-orange-600">
                        {{ $attendances->where('status', 'incomplete')->count() }}
                    </div>
                    <div class="text-sm text-gray-600">Not finished</div>
                </div>
            </div>

            <!-- Attendance Records -->
            <div class="bg-white border border-gray-300 shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-black">
                            Employee Attendance Records - {{ today()->format('M d, Y') }}
                        </h3>
                        
                        <!-- Export Button (Future Enhancement) -->
                        <button class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg" disabled>
                            Export to Excel
                        </button>
                    </div>

                    @if($attendances->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                                            Employee
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                                            Clock In
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                                            Clock Out
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                                            Working Hours
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($attendances as $attendance)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-gray-700">
                                                                {{ substr($attendance->user->name, 0, 2) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-black">{{ $attendance->user->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $attendance->user->employee_id }}</div>
                                                        <div class="text-sm text-gray-500">{{ $attendance->user->department ?? 'No Department' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-black">
                                                    {{ $attendance->formatted_clock_in ?? '-' }}
                                                </div>
                                                @if($attendance->hasCheckedIn() && $attendance->isLate())
                                                    <div class="text-xs text-red-600 font-medium">Late</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                {{ $attendance->formatted_clock_out ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-black">
                                                    {{ $attendance->working_hours_display ?? '-' }}
                                                </div>
                                                @if($attendance->getOvertimeHours() > 0)
                                                    <div class="text-xs text-blue-600">
                                                        +{{ $attendance->getOvertimeHours() }}h OT
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($attendance->status == 'present') bg-green-100 text-green-800
                                                    @elseif($attendance->status == 'late') bg-yellow-100 text-yellow-800
                                                    @elseif($attendance->status == 'incomplete') bg-orange-100 text-orange-800
                                                    @elseif($attendance->status == 'absent') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($attendance->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                @if($attendance->clock_in_photo || $attendance->clock_out_photo)
                                                    <button class="text-blue-600 hover:text-blue-900" 
                                                            onclick="viewPhotos('{{ $attendance->id }}')">
                                                        Photos
                                                    </button>
                                                @endif
                                                
                                                <button class="text-indigo-600 hover:text-indigo-900" 
                                                        onclick="editAttendance('{{ $attendance->id }}', '{{ $attendance->clock_in_time }}', '{{ $attendance->clock_out_time }}', '{{ $attendance->status }}', '{{ $attendance->notes ?? '' }}')">
                                                    Edit
                                                </button>
                                                
                                                <form method="POST" action="{{ route('attendance.destroy', $attendance) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900"
                                                            onclick="return confirm('Are you sure you want to delete this attendance record?')">
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
                            <div class="text-gray-500 text-lg mb-4">
                                No attendance records found for the selected criteria.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Attendance Modal -->
    <div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 max-w-md mx-4 w-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-black">Edit Attendance Record</h3>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
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
                        <label for="edit-clock-in" class="block text-sm font-medium text-black mb-1">Clock In Time</label>
                        <input type="time" id="edit-clock-in" name="clock_in_time" 
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="edit-clock-out" class="block text-sm font-medium text-black mb-1">Clock Out Time</label>
                        <input type="time" id="edit-clock-out" name="clock_out_time" 
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="edit-status" class="block text-sm font-medium text-black mb-1">Status</label>
                        <select id="edit-status" name="status" 
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="present">Present</option>
                            <option value="late">Late</option>
                            <option value="incomplete">Incomplete</option>
                            <option value="absent">Absent</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="edit-notes" class="block text-sm font-medium text-black mb-1">Notes</label>
                        <textarea id="edit-notes" name="notes" rows="3" 
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeEditModal()" 
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="bg-blue-900 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
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
            // Future: Display attendance photos
            alert('Photo viewing feature will be implemented in the next phase.');
        }
    </script>
</x-app-layout>