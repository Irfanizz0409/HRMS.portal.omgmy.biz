<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Clock In/Out System
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
        <div class="max-w-5xl mx-auto px-4 py-8">
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

            <!-- Attendance Status -->
            <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl mb-8">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-white mb-8 text-center">Today's Attendance</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="text-center bg-emerald-500/20 border border-emerald-400/30 p-6 rounded-xl">
                            <div class="text-sm font-semibold text-emerald-300 mb-2">Clock In</div>
                            <div class="text-2xl font-bold text-emerald-100">
                                @if($attendance && $attendance->hasCheckedIn())
                                    {{ $attendance->formatted_clock_in }}
                                @else
                                    Not clocked in
                                @endif
                            </div>
                        </div>
                        
                        <div class="text-center bg-rose-500/20 border border-rose-400/30 p-6 rounded-xl">
                            <div class="text-sm font-semibold text-rose-300 mb-2">Clock Out</div>
                            <div class="text-2xl font-bold text-rose-100">
                                @if($attendance && $attendance->hasCheckedOut())
                                    {{ $attendance->formatted_clock_out }}
                                @else
                                    Not clocked out
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($attendance && $attendance->total_hours)
                        <div class="mt-6 text-center bg-blue-500/20 border border-blue-400/30 p-6 rounded-xl">
                            <div class="text-sm font-semibold text-blue-300 mb-2">Total Hours</div>
                            <div class="text-3xl font-bold text-blue-100">{{ $attendance->total_hours }} hours</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Photo Section -->
            <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-2xl mb-8">
                <div class="p-8">
                    <h4 class="text-2xl font-bold text-white mb-8 text-center">Photo Verification</h4>
                    
                    <div class="flex flex-col items-center space-y-6">
                        <!-- Camera/Photo Display -->
                        <div class="relative">
                            <video id="camera" width="400" height="300" autoplay class="border-2 border-purple-400 rounded-xl" style="display: none;"></video>
                            <canvas id="photo-canvas" width="400" height="300" style="display: none;"></canvas>
                            
                            <div id="photo-preview" class="border-2 border-emerald-400 rounded-xl" style="width: 400px; height: 300px; display: none;">
                                <img id="captured-photo" src="" alt="Captured photo" class="w-full h-full object-cover rounded-xl">
                            </div>
                            
                            <div id="camera-placeholder" class="border-2 border-dashed border-gray-400 rounded-xl bg-white/5 flex items-center justify-center cursor-pointer hover:bg-white/10 transition-all duration-300" style="width: 400px; height: 300px;" onclick="document.getElementById('start-camera').click()">
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
                                Confirm Photo
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
                                <div class="text-emerald-200 text-lg">You have clocked in and out for today</div>
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
    </div>

    <script>
        let camera = document.getElementById('camera');
        let canvas = document.getElementById('photo-canvas');
        let context = canvas.getContext('2d');
        let capturedPhotoData = null;

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
                alert('Camera access denied: ' + error.message);
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
            
            alert('Photo captured! Please review and confirm if you are satisfied.');
        });

        document.getElementById('confirm-photo').addEventListener('click', function() {
            if (confirm('Are you satisfied with this photo? Click OK to save and proceed.')) {
                document.getElementById('confirm-photo').style.display = 'none';
                enableClockButtons();
                alert('Photo confirmed and saved! You can now proceed with attendance.');
            }
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
                    alert('Please take a photo first');
                    return;
                }

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
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        clockInBtn.disabled = false;
                        clockInBtn.textContent = 'CLOCK IN';
                    }
                });
            });
        }

        const clockOutBtn = document.getElementById('clock-out-btn');
        if (clockOutBtn) {
            clockOutBtn.addEventListener('click', function() {
                if (!capturedPhotoData) {
                    alert('Please take a photo first');
                    return;
                }

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
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        clockOutBtn.disabled = false;
                        clockOutBtn.textContent = 'CLOCK OUT';
                    }
                });
            });
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
    </script>
</x-app-layout>