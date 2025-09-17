@extends('layouts.sidebar')

@section('page-title', 'Clock In/Out System')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 rounded-lg p-6">
    <!-- Current Time Display -->
    <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl mb-8 overflow-hidden">
        <div class="p-8 text-center">
            <div class="text-6xl font-bold text-white mb-2" id="current-time">
                --:--:--
            </div>
            <div class="text-xl text-gray-300" id="current-date">
                Loading...
            </div>
        </div>
    </div>

    <!-- Work Schedule Info -->
    <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl mb-8">
        <div class="p-6">
            <h3 class="text-xl font-bold text-white mb-4 text-center">Today's Work Schedule</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                @php
                    $userSettings = \App\Models\UserWorkSetting::where('user_id', auth()->id())->first();
                    $clockInDeadline = $userSettings ? date('g:i A', strtotime($userSettings->clock_in_deadline)) : '9:45 AM';
                    $startTime = $userSettings ? date('g:i A', strtotime($userSettings->preferred_start_time)) : '10:00 AM';
                    $endTime = $userSettings ? date('g:i A', strtotime($userSettings->preferred_end_time)) : '6:00 PM';
                    $requiredHours = $userSettings ? $userSettings->required_hours_per_day : '8.00';
                @endphp
                
                <div class="bg-blue-500/20 border border-blue-400/30 p-4 rounded-xl">
                    <div class="text-sm text-blue-300">Clock In Before</div>
                    <div class="text-lg font-bold text-blue-100">{{ $clockInDeadline }}</div>
                </div>
                <div class="bg-green-500/20 border border-green-400/30 p-4 rounded-xl">
                    <div class="text-sm text-green-300">Work Hours</div>
                    <div class="text-lg font-bold text-green-100">{{ $startTime }} - {{ $endTime }}</div>
                </div>
                <div class="bg-purple-500/20 border border-purple-400/30 p-4 rounded-xl">
                    <div class="text-sm text-purple-300">Required Hours</div>
                    <div class="text-lg font-bold text-purple-100">{{ $requiredHours }} hours</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Real-Time Timer (only show when clocked in but not clocked out) -->
    @if($attendance && $attendance->hasCheckedIn() && !$attendance->hasCheckedOut())
    <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl mb-8">
        <div class="p-8 text-center">
            <h3 class="text-2xl font-bold text-white mb-4">Working Time</h3>
            <div class="text-6xl font-bold text-green-400 mb-2" id="live-timer">
                00:00:00
            </div>
            <div class="text-lg text-green-300">Started at {{ $attendance->formatted_clock_in }}</div>
            <div class="text-sm text-gray-300 mt-2">Timer will stop when you clock out</div>
        </div>
    </div>
    @endif

    <!-- Attendance Status -->
    <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl mb-8">
        <div class="p-8">
            <h3 class="text-2xl font-bold text-white mb-8 text-center">Today's Attendance</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="text-center bg-emerald-500/20 border border-emerald-400/30 p-6 rounded-xl">
                    <div class="text-sm font-semibold text-emerald-300 mb-2">Clock In</div>
                    <div class="text-2xl font-bold text-emerald-100" id="clock-in-display">
                        @if($attendance && $attendance->hasCheckedIn())
                            {{ $attendance->formatted_clock_in }}
                        @else
                            Not clocked in
                        @endif
                    </div>
                    <div class="text-sm text-emerald-200 mt-2" id="clock-in-status">
                        @if($attendance && $attendance->hasCheckedIn())
                            @php
                                $userSettings = \App\Models\UserWorkSetting::where('user_id', auth()->id())->first();
                                $deadline = $userSettings ? $userSettings->clock_in_deadline : '09:45:00';
                                $clockInTime = \Carbon\Carbon::createFromFormat('H:i:s', $attendance->clock_in_time);
                                $deadlineTime = \Carbon\Carbon::createFromFormat('H:i:s', $deadline);
                                $isLate = $clockInTime->gt($deadlineTime);
                            @endphp
                            
                            @if($isLate)
                                <span class="text-red-300">Late Arrival</span>
                            @else
                                <span class="text-green-300">On Time</span>
                            @endif
                        @endif
                    </div>
                </div>
                
                <div class="text-center bg-rose-500/20 border border-rose-400/30 p-6 rounded-xl">
                    <div class="text-sm font-semibold text-rose-300 mb-2">Clock Out</div>
                    <div class="text-2xl font-bold text-rose-100" id="clock-out-display">
                        @if($attendance && $attendance->hasCheckedOut())
                            {{ $attendance->formatted_clock_out }}
                        @else
                            Not clocked out
                        @endif
                    </div>
                    <div class="text-sm text-rose-200 mt-2" id="hours-worked">
                        @if($attendance && $attendance->total_hours)
                            {{ $attendance->total_hours }} hours worked
                        @endif
                    </div>
                </div>
            </div>

            @if($attendance && $attendance->total_hours)
                @php
                    $userSettings = \App\Models\UserWorkSetting::where('user_id', auth()->id())->first();
                    $requiredHours = $userSettings ? $userSettings->required_hours_per_day : 8.00;
                @endphp
                <div class="mt-6 text-center bg-blue-500/20 border border-blue-400/30 p-6 rounded-xl">
                    <div class="text-sm font-semibold text-blue-300 mb-2">Progress</div>
                    <div class="text-3xl font-bold text-blue-100 mb-2">{{ $attendance->total_hours }}/{{ $requiredHours }} hours</div>
                    <div class="w-full bg-gray-700 rounded-full h-3">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-3 rounded-full transition-all duration-500" 
                             style="width: {{ min(($attendance->total_hours / $requiredHours) * 100, 100) }}%"></div>
                    </div>
                    <div class="text-sm text-blue-200 mt-2">
                        @if($attendance->total_hours >= $requiredHours)
                            <span class="text-green-300 font-bold">Complete Day!</span>
                        @else
                            {{ number_format($requiredHours - $attendance->total_hours, 1) }} hours remaining
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Photo Section -->
    <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl mb-8">
        <div class="p-8">
            <h4 class="text-2xl font-bold text-white mb-8 text-center">Photo Verification Required</h4>
            
            <div class="flex flex-col items-center space-y-6">
                <!-- Camera/Photo Display -->
                <div class="relative">
                    <video id="camera" width="400" height="300" autoplay class="border-2 border-purple-400 rounded-xl" style="display: none;"></video>
                    <canvas id="photo-canvas" width="400" height="300" style="display: none;"></canvas>
                    
                    <div id="photo-preview" class="border-2 border-emerald-400 rounded-xl" style="width: 400px; height: 300px; display: none;">
                        <img id="captured-photo" src="" alt="Captured photo" class="w-full h-full object-cover rounded-xl">
                    </div>
                    
                    <div id="camera-placeholder" class="border-2 border-dashed border-gray-400 rounded-xl bg-white/5 flex items-center justify-center cursor-pointer hover:bg-white/10 transition-all duration-300" style="width: 400px; height: 300px;" onclick="startCamera()">
                        <div class="text-center">
                            <div class="text-gray-300 mb-3">
                                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0118.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-200 font-semibold text-lg">Click to start camera</p>
                        </div>
                    </div>
                </div>
                
                <!-- Photo Controls -->
                <div class="flex flex-wrap justify-center gap-4">
                    <button id="start-camera" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 px-8 rounded-xl shadow-xl transition-all duration-300 transform hover:scale-105 text-lg">
                        Start Camera
                    </button>
                    <button id="capture-photo" class="bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-bold py-4 px-8 rounded-xl shadow-xl transition-all duration-300 transform hover:scale-105 text-lg" style="display: none;">
                        Take Photo
                    </button>
                    <button id="confirm-photo" class="bg-gradient-to-r from-violet-600 to-purple-700 hover:from-violet-700 hover:to-purple-800 text-white font-bold py-4 px-8 rounded-xl shadow-xl transition-all duration-300 transform hover:scale-105 text-lg" style="display: none;">
                        Use This Photo
                    </button>
                    <button id="retake-photo" class="bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 text-white font-bold py-4 px-8 rounded-xl shadow-xl transition-all duration-300 transform hover:scale-105 text-lg" style="display: none;">
                        Retake Photo
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Clock Buttons -->
    <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl mb-8">
        <div class="p-8">
            <div class="flex justify-center">
                @if(!$attendance || !$attendance->hasCheckedIn())
                    <button id="clock-in-btn" class="bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 disabled:from-gray-500 disabled:to-gray-600 disabled:cursor-not-allowed text-white font-bold py-6 px-16 rounded-2xl text-2xl disabled:opacity-60 shadow-2xl transition-all duration-300 transform hover:scale-105 disabled:transform-none" disabled>
                        CLOCK IN
                    </button>
                @elseif(!$attendance->hasCheckedOut())
                    <button id="clock-out-btn" class="bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-700 hover:to-red-700 disabled:from-gray-500 disabled:to-gray-600 disabled:cursor-not-allowed text-white font-bold py-6 px-16 rounded-2xl text-2xl disabled:opacity-60 shadow-2xl transition-all duration-300 transform hover:scale-105 disabled:transform-none" disabled>
                        CLOCK OUT
                    </button>
                @else
                    <div class="text-center bg-emerald-500/20 border border-emerald-400/40 p-8 rounded-2xl">
                        <div class="w-16 h-16 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="text-emerald-100 font-bold text-2xl mb-2">Attendance Complete</div>
                        <div class="text-emerald-200 text-lg">You have completed your work day</div>
                        @php
                            $userSettings = \App\Models\UserWorkSetting::where('user_id', auth()->id())->first();
                            $requiredHours = $userSettings ? $userSettings->required_hours_per_day : 8.00;
                        @endphp
                        @if($attendance->total_hours >= $requiredHours)
                            <div class="text-emerald-300 text-sm mt-2">Full {{ $requiredHours }} hours completed!</div>
                        @endif
                    </div>
                @endif
            </div>

            <div id="status-message" class="text-center hidden mt-6">
                <div id="status-content" class="p-6 rounded-xl font-bold text-lg"></div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="text-center space-x-6">
        <a href="{{ route('attendance.index') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-4 px-8 rounded-xl inline-block shadow-xl transition-all duration-300 transform hover:scale-105 text-lg">
            View Dashboard
        </a>
        <a href="{{ route('attendance.history') }}" class="bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 text-white font-bold py-4 px-8 rounded-xl inline-block shadow-xl transition-all duration-300 transform hover:scale-105 text-lg">
            View History
        </a>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmation-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-md mx-4 w-full">
        <div class="text-center">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 id="modal-title" class="text-xl font-bold text-gray-900 mb-2">Confirm Clock In</h3>
            <p id="modal-message" class="text-gray-600 mb-6">Are you sure you want to clock in now?</p>
            <div class="flex space-x-4">
                <button id="modal-cancel" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-6 rounded-lg transition-colors">
                    Cancel
                </button>
                <button id="modal-confirm" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let camera = document.getElementById('camera');
    let canvas = document.getElementById('photo-canvas');
    let context = canvas.getContext('2d');
    let capturedPhotoData = null;
    let currentAction = null;

    function updateTime() {
        const now = new Date();
        document.getElementById('current-time').textContent = now.toLocaleTimeString();
        document.getElementById('current-date').textContent = now.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    setInterval(updateTime, 1000);
    updateTime();

    // Real-time timer for active work session
    @if($attendance && $attendance->hasCheckedIn() && !$attendance->hasCheckedOut())
    function startWorkTimer() {
        const clockInTime = new Date('{{ $attendance->date->format("Y-m-d") }} {{ $attendance->clock_in_time }}');
        const timerElement = document.getElementById('live-timer');
        
        function updateTimer() {
            const now = new Date();
            const diff = now - clockInTime;
            
            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);
            
            const display = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            timerElement.textContent = display;
        }
        
        // Update immediately and then every second
        updateTimer();
        setInterval(updateTimer, 1000);
    }

    // Start timer when page loads
    document.addEventListener('DOMContentLoaded', function() {
        startWorkTimer();
    });
    @endif

    function startCamera() {
        document.getElementById('start-camera').click();
    }

    document.getElementById('start-camera').addEventListener('click', async function() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ 
                video: { width: 400, height: 300, facingMode: 'user' } 
            });
            camera.srcObject = stream;
            camera.style.display = 'block';
            document.getElementById('camera-placeholder').style.display = 'none';
            document.getElementById('start-camera').style.display = 'none';
            document.getElementById('capture-photo').style.display = 'inline-block';
        } catch (error) {
            showAlert('Error', 'Camera access denied: ' + error.message, 'error');
        }
    });

    document.getElementById('capture-photo').addEventListener('click', function() {
        context.drawImage(camera, 0, 0, 400, 300);
        capturedPhotoData = canvas.toDataURL('image/jpeg', 0.8);
        
        document.getElementById('captured-photo').src = capturedPhotoData;
        document.getElementById('photo-preview').style.display = 'block';
        camera.style.display = 'none';
        document.getElementById('capture-photo').style.display = 'none';
        document.getElementById('confirm-photo').style.display = 'inline-block';
        document.getElementById('retake-photo').style.display = 'inline-block';
        
        let stream = camera.srcObject;
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
        
        showAlert('Photo Captured', 'Please review your photo. Are you satisfied with this image for attendance?', 'info');
    });

    document.getElementById('confirm-photo').addEventListener('click', function() {
        showConfirmation(
            'Confirm Photo Usage', 
            'Are you sure you want to use this photo for attendance? This cannot be changed later.',
            function() {
                document.getElementById('confirm-photo').style.display = 'none';
                enableClockButtons();
                showAlert('Success', 'Photo confirmed and saved! You can now proceed with attendance.', 'success');
            }
        );
    });

    document.getElementById('retake-photo').addEventListener('click', function() {
        document.getElementById('photo-preview').style.display = 'none';
        document.getElementById('retake-photo').style.display = 'none';
        document.getElementById('confirm-photo').style.display = 'none';
        document.getElementById('camera-placeholder').style.display = 'flex';
        document.getElementById('start-camera').style.display = 'inline-block';
        capturedPhotoData = null;
        disableClockButtons();
    });

    function enableClockButtons() {
        const clockInBtn = document.getElementById('clock-in-btn');
        const clockOutBtn = document.getElementById('clock-out-btn');
        if (clockInBtn) clockInBtn.disabled = false;
        if (clockOutBtn) clockOutBtn.disabled = false;
    }

    function disableClockButtons() {
        const clockInBtn = document.getElementById('clock-in-btn');
        const clockOutBtn = document.getElementById('clock-out-btn');
        if (clockInBtn) clockInBtn.disabled = true;
        if (clockOutBtn) clockOutBtn.disabled = true;
    }

    const clockInBtn = document.getElementById('clock-in-btn');
    if (clockInBtn) {
        clockInBtn.addEventListener('click', function() {
            if (!capturedPhotoData) {
                showAlert('Photo Required', 'Please take a photo first before clocking in.', 'error');
                return;
            }

            const now = new Date();
            const currentTime = now.toTimeString().substr(0, 5);
            let message = 'Are you sure you want to clock in now?';
            
            @php
                $userSettings = \App\Models\UserWorkSetting::where('user_id', auth()->id())->first();
                $deadline = $userSettings ? $userSettings->clock_in_deadline : '09:45:00';
                $deadlineDisplay = date('g:i', strtotime($deadline));
            @endphp
            
            if (currentTime > '{{ $deadlineDisplay }}') {
                message += ' You are clocking in after {{ date("g:i A", strtotime($deadline)) }} and will be marked as LATE.';
            }

            showConfirmation(
                'Confirm Clock In',
                message,
                function() {
                    performClockIn();
                }
            );
        });
    }

    const clockOutBtn = document.getElementById('clock-out-btn');
    if (clockOutBtn) {
        clockOutBtn.addEventListener('click', function() {
            if (!capturedPhotoData) {
                showAlert('Photo Required', 'Please take a photo first before clocking out.', 'error');
                return;
            }

            showConfirmation(
                'Confirm Clock Out',
                'Are you sure you want to clock out now? Make sure you have completed your work hours.',
                function() {
                    performClockOut();
                }
            );
        });
    }

    function performClockIn() {
        const clockInBtn = document.getElementById('clock-in-btn');
        clockInBtn.disabled = true;
        clockInBtn.textContent = 'PROCESSING...';

        fetch('{{ route("attendance.clock-in") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                photo: capturedPhotoData,
                location: 'Office'
            })
        })
        .then(response => response.json())
        .then(data => {
            showStatusMessage(data.message, data.success ? 'success' : 'error');
            if (data.success) {
                showAlert('Clock In Successful!', `You have successfully clocked in at ${data.time}. Status: ${data.status.toUpperCase()}`, 'success');
                setTimeout(() => location.reload(), 3000);
            } else {
                clockInBtn.disabled = false;
                clockInBtn.textContent = 'CLOCK IN';
            }
        });
    }

    function performClockOut() {
        const clockOutBtn = document.getElementById('clock-out-btn');
        clockOutBtn.disabled = true;
        clockOutBtn.textContent = 'PROCESSING...';

        fetch('{{ route("attendance.clock-out") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                photo: capturedPhotoData,
                location: 'Office'
            })
        })
        .then(response => response.json())
        .then(data => {
            showStatusMessage(data.message, data.success ? 'success' : 'error');
            if (data.success) {
                let message = `You have successfully clocked out at ${data.time}. Total hours: ${data.total_hours}`;
                @php
                    $userSettings = \App\Models\UserWorkSetting::where('user_id', auth()->id())->first();
                    $requiredHours = $userSettings ? $userSettings->required_hours_per_day : 8.00;
                @endphp
                if (data.total_hours >= {{ $requiredHours }}) {
                    message += ' - Complete work day!';
                }
                showAlert('Clock Out Successful!', message, 'success');
                setTimeout(() => location.reload(), 3000);
            } else {
                clockOutBtn.disabled = false;
                clockOutBtn.textContent = 'CLOCK OUT';
            }
        });
    }

    function showConfirmation(title, message, onConfirm) {
        document.getElementById('modal-title').textContent = title;
        document.getElementById('modal-message').textContent = message;
        document.getElementById('confirmation-modal').classList.remove('hidden');
        document.getElementById('confirmation-modal').classList.add('flex');

        document.getElementById('modal-cancel').onclick = function() {
            document.getElementById('confirmation-modal').classList.add('hidden');
            document.getElementById('confirmation-modal').classList.remove('flex');
        };

        document.getElementById('modal-confirm').onclick = function() {
            document.getElementById('confirmation-modal').classList.add('hidden');
            document.getElementById('confirmation-modal').classList.remove('flex');
            onConfirm();
        };
    }

    function showStatusMessage(message, type) {
        const statusDiv = document.getElementById('status-message');
        const contentDiv = document.getElementById('status-content');
        
        contentDiv.textContent = message;
        contentDiv.className = 'p-6 rounded-xl font-bold text-lg ' + 
            (type === 'success' ? 'bg-emerald-500/20 text-emerald-100 border border-emerald-400/40' : 'bg-rose-500/20 text-rose-100 border border-rose-400/40');
        statusDiv.classList.remove('hidden');
        
        setTimeout(() => {
            statusDiv.classList.add('hidden');
        }, 5000);
    }

    function showAlert(title, message, type) {
        // Simple alert for now, can be enhanced with better modal
        alert(title + '\n\n' + message);
    }
</script>
@endsection