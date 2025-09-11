<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Attendance History
            </h2>
            <a href="{{ route('attendance.clock') }}" 
               class="bg-blue-900 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg">
                Clock In/Out
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Date Range Filter -->
            <div class="bg-white border border-gray-300 shadow-lg rounded-xl mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-black mb-4">Filter by Date Range</h3>
                    
                    <form method="GET" action="{{ route('attendance.history') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-black mb-1">Start Date</label>
                            <input type="date" name="start_date" id="start_date" 
                                   value="{{ request('start_date', $startDate) }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-black mb-1">End Date</label>
                            <input type="date" name="end_date" id="end_date" 
                                   value="{{ request('end_date', $endDate) }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="bg-blue-900 hover:bg-blue-800 text-white font-bold py-2 px-6 rounded-lg mr-2">
                                Filter
                            </button>
                            <a href="{{ route('attendance.history') }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Attendance History -->
            <div class="bg-white border border-gray-300 shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-black">
                            My Attendance Records
                        </h3>
                        
                        <div class="text-sm text-gray-600">
                            Showing {{ $attendances->count() }} of {{ $attendances->total() }} records
                        </div>
                    </div>

                    @if($attendances->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                                            Date
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
                                                <div class="text-sm font-medium text-black">
                                                    {{ $attendance->date->format('M d, Y') }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $attendance->date->format('l') }}
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
                                                    @if($attendance->status == 'present') bg-green-100 text-green-800
                                                    @elseif($attendance->status == 'late') bg-yellow-100 text-yellow-800
                                                    @elseif($attendance->status == 'incomplete') bg-orange-100 text-orange-800
                                                    @elseif($attendance->status == 'absent') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($attendance->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if($attendance->clock_in_photo || $attendance->clock_out_photo)
                                                    <button class="text-blue-600 hover:text-blue-900 mr-2" 
                                                            onclick="viewPhotos('{{ $attendance->id }}')">
                                                        View Photos
                                                    </button>
                                                @endif
                                                @if($attendance->notes)
                                                    <button class="text-gray-600 hover:text-gray-900" 
                                                            onclick="viewNotes('{{ $attendance->notes }}')">
                                                        Notes
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $attendances->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-500 text-lg mb-4">
                                No attendance records found for the selected period.
                            </div>
                            <a href="{{ route('attendance.clock') }}" 
                               class="bg-blue-900 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg">
                                Start Attendance
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 text-center">
                <a href="{{ route('attendance.clock') }}" 
                   class="bg-blue-900 hover:bg-blue-800 text-white font-bold py-3 px-8 rounded-lg mr-4 inline-block">
                    Clock In/Out
                </a>
                <a href="{{ route('attendance.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-8 rounded-lg inline-block">
                    Dashboard
                </a>
            </div>
        </div>
    </div>

    <script>
        function viewPhotos(attendanceId) {
            fetch(`/portal/public/attendance/${attendanceId}/photos`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        let photoHtml = '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
                        
                        if (data.clock_in_photo) {
                            photoHtml += `
                                <div class="text-center">
                                    <h4 class="font-bold mb-2">Clock In Photo</h4>
                                    <img src="/portal/public/storage/${data.clock_in_photo}" alt="Clock In" class="w-full h-48 object-cover rounded border">
                                </div>
                            `;
                        }
                        
                        if (data.clock_out_photo) {
                            photoHtml += `
                                <div class="text-center">
                                    <h4 class="font-bold mb-2">Clock Out Photo</h4>
                                    <img src="/storage/${data.clock_out_photo}" alt="Clock Out" class="w-full h-48 object-cover rounded border">
                                </div>
                            `;
                        }
                        
                        photoHtml += '</div>';
                        
                        const modal = document.createElement('div');
                        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                        modal.innerHTML = `
                            <div class="bg-white rounded-xl p-6 max-w-4xl mx-4">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-bold">My Attendance Photos</h3>
                                    <button onclick="this.closest('.fixed').remove()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
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

        function viewNotes(notes) {
            alert('Notes: ' + notes);
        }
    </script>
</x-app-layout>