<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Attendance Dashboard
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Today's Status -->
                <div class="bg-white border border-gray-300 shadow-lg rounded-xl p-6">
                    <h3 class="text-lg font-bold text-black mb-2">Today's Status</h3>
                    @if($todayAttendance)
                        @if($todayAttendance->hasCheckedIn() && $todayAttendance->hasCheckedOut())
                            <div class="text-2xl font-bold text-green-600">Complete</div>
                            <div class="text-sm text-gray-600">{{ $todayAttendance->working_hours_display }}</div>
                        @elseif($todayAttendance->hasCheckedIn())
                            <div class="text-2xl font-bold text-blue-600">Clocked In</div>
                            <div class="text-sm text-gray-600">Since {{ $todayAttendance->formatted_clock_in }}</div>
                        @else
                            <div class="text-2xl font-bold text-gray-600">Not Started</div>
                        @endif
                    @else
                        <div class="text-2xl font-bold text-gray-600">Not Started</div>
                    @endif
                </div>

                <!-- This Week -->
                <div class="bg-white border border-gray-300 shadow-lg rounded-xl p-6">
                    <h3 class="text-lg font-bold text-black mb-2">This Week</h3>
                    <div class="text-2xl font-bold text-blue-900">{{ $recentAttendance->count() }}</div>
                    <div class="text-sm text-gray-600">Days attended</div>
                </div>

                <!-- Total Hours -->
                <div class="bg-white border border-gray-300 shadow-lg rounded-xl p-6">
                    <h3 class="text-lg font-bold text-black mb-2">Weekly Hours</h3>
                    <div class="text-2xl font-bold text-blue-900">
                        {{ number_format($recentAttendance->sum('total_hours'), 1) }}h
                    </div>
                    <div class="text-sm text-gray-600">Total worked</div>
                </div>

                <!-- Average Hours -->
                <div class="bg-white border border-gray-300 shadow-lg rounded-xl p-6">
                    <h3 class="text-lg font-bold text-black mb-2">Average Daily</h3>
                    <div class="text-2xl font-bold text-blue-900">
                        @if($recentAttendance->count() > 0)
                            {{ number_format($recentAttendance->avg('total_hours'), 1) }}h
                        @else
                            0h
                        @endif
                    </div>
                    <div class="text-sm text-gray-600">Per day</div>
                </div>
            </div>

            <!-- Today's Detailed Status -->
            @if($todayAttendance)
            <div class="bg-white border border-gray-300 shadow-lg rounded-xl mb-8">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-black mb-6">Today's Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Clock In -->
                        <div class="text-center bg-gray-50 p-6 rounded-xl">
                            <div class="text-sm font-medium text-black mb-2">Clock In</div>
                            <div class="text-2xl font-bold text-green-600">
                                {{ $todayAttendance->formatted_clock_in ?? 'Not clocked in' }}
                            </div>
                            @if($todayAttendance->hasCheckedIn())
                                <div class="text-sm text-gray-600 mt-1">
                                    @if($todayAttendance->isLate())
                                        <span class="text-red-600 font-medium">Late Arrival</span>
                                    @else
                                        <span class="text-green-600 font-medium">On Time</span>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Clock Out -->
                        <div class="text-center bg-gray-50 p-6 rounded-xl">
                            <div class="text-sm font-medium text-black mb-2">Clock Out</div>
                            <div class="text-2xl font-bold text-red-600">
                                {{ $todayAttendance->formatted_clock_out ?? 'Not clocked out' }}
                            </div>
                            @if($todayAttendance->hasCheckedOut())
                                <div class="text-sm text-gray-600 mt-1">
                                    @if($todayAttendance->getOvertimeHours() > 0)
                                        <span class="text-blue-600 font-medium">{{ $todayAttendance->getOvertimeHours() }}h Overtime</span>
                                    @else
                                        <span class="text-gray-600">Regular Hours</span>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Total Hours -->
                        <div class="text-center bg-gray-50 p-6 rounded-xl">
                            <div class="text-sm font-medium text-black mb-2">Total Hours</div>
                            <div class="text-2xl font-bold text-blue-900">
                                {{ $todayAttendance->working_hours_display ?? '0h' }}
                            </div>
                            <div class="text-sm text-gray-600 mt-1">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($todayAttendance->status == 'present') bg-green-100 text-green-800
                                    @elseif($todayAttendance->status == 'late') bg-yellow-100 text-yellow-800
                                    @elseif($todayAttendance->status == 'incomplete') bg-orange-100 text-orange-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($todayAttendance->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Attendance -->
            <div class="bg-white border border-gray-300 shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-black">Recent Attendance (Last 7 Days)</h3>
                        <a href="{{ route('attendance.history') }}" 
                           class="bg-blue-900 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg">
                            View Full History
                        </a>
                    </div>

                    @if($recentAttendance->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Clock In</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Clock Out</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Hours</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentAttendance as $attendance)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                {{ $attendance->date->format('M d, Y') }}
                                                <div class="text-xs text-gray-500">{{ $attendance->date->format('l') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                {{ $attendance->formatted_clock_in ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                {{ $attendance->formatted_clock_out ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                                {{ $attendance->working_hours_display ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($attendance->status == 'present') bg-green-100 text-green-800
                                                    @elseif($attendance->status == 'late') bg-yellow-100 text-yellow-800
                                                    @elseif($attendance->status == 'incomplete') bg-orange-100 text-orange-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($attendance->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-500 mb-4">No attendance records found for the past week.</div>
                            <a href="{{ route('attendance.clock') }}" 
                               class="bg-blue-900 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg">
                                Clock In Now
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
                <a href="{{ route('attendance.history') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-8 rounded-lg inline-block">
                    Full History
                </a>
            </div>
        </div>
    </div>
</x-app-layout>