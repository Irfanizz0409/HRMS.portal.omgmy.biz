<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Clock In/Out System
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <!-- Current Time Display -->
            <div class="bg-white border-2 border-blue-900 shadow-xl sm:rounded-2xl mb-8">
                <div class="p-8 text-center">
                    <div class="text-7xl font-bold text-blue-900 mb-3" id="current-time">
                        --:--:--
                    </div>
                    <div class="text-xl text-gray-600 font-medium" id="current-date">
                        Loading...
                    </div>
                </div>
            </div>

            <!-- Attendance Status -->
            <div class="bg-white border border-gray-300 shadow-lg sm:rounded-2xl mb-8">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Today's Attendance Status</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="text-center bg-gray-50 p-6 rounded-xl border border-gray-200">
                            <div class="text-lg font-medium text-gray-600 mb-2">Clock In Time</div>
                            <div class="text-3xl font-bold text-green-600">
                                @if($attendance && $attendance->hasCheckedIn())
                                    {{ $attendance->formatted_clock_in }}
                                @else
                                    Not clocked in
                                @endif
                            </div>
                        </div>
                        
                        <div class="text-center bg-gray-50 p-6 rounded-xl border border-gray-200">
                            <div class="text-lg font-medium text-gray-600 mb-2">Clock Out Time</div>
                            <div class="text-3xl font-bold text-red-600">
                                @if($attendance && $attendance->hasCheckedOut())
                                    {{ $attendance->formatted_clock_out }}
                                @else
                                    Not clocked out
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($attendance && $attendance->total_hours)
                        <div class="mt-8 text-center bg-blue-50 p-6 rounded-xl border border-blue-200">
                            <div class="text-lg font-medium text-gray-600 mb-2">Total Working Hours</div>
                            <div class="text-4xl font-bold text-blue-900">{{ $attendance->total_hours }} hours</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Clock In/Out Actions -->
            <div class="bg-white border border-gray-300 shadow-lg sm:rounded-2xl">
                <div class="p-8">
                    <!-- Camera Section -->
                    <div class="mb-10">
                        <h4 class="text-2xl font-bold text-black mb-6 text-center">Photo Verification Required</h4>
                        
                        <div class="flex flex-col items-center space-y-6">
                            <!-- Camera Video -->
                            <video id="camera" width="400" height="300" autoplay class="border-4 border-blue-900 rounded-2xl bg-gray-100 shadow-lg" style="display: none;"></video>
                            
                            <!-- Photo Canvas (hidden) -->
                            <canvas id="photo-canvas" width="400" height="300" style="display: none;"></canvas>
                            
                            <!-- Photo Preview -->
                            <div id="photo-preview" class="border-4 border-green-600 rounded-2xl bg-gray-100 shadow-lg" style="width: 400px; height: 300px; display: none;">
                                <img id="captured-photo" src="" alt="Captured photo" class="w-full h-full object-cover rounded-xl">
                            </div>
                            
                            <!-- Camera Placeholder -->
                            <div id="camera-placeholder" class="border-4 border-dashed border-gray-400 rounded-2xl bg-gray-50 flex items-center justify-center" style="width: 400px; height: 300px;">
                                <div class="text-center">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <p class="text-gray-600 font-medium">Camera will appear here</p>
                                </div>
                            </div>
                            
                            <!-- Camera Controls -->
                            <div class="flex space-x-4">
                                <button id="start-camera" class="bg-blue-900 hover:bg-blue-800 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition-all duration-200 text-lg">
                                    Start Camera
                                </button>
                                <button id="capture-photo" class="bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition-all duration-200 text-lg" style="display: none;">
                                    Capture Photo
                                </button>
                                <button id="retake-photo" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition-all duration-200 text-lg" style="display: none;">
                                    Retake Photo
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Clock In/Out Buttons -->
                    <div class="flex justify-center space-x-6 mb-8">
                        @if(!$attendance || !$attendance->hasCheckedIn())
                            <button id="clock-in-btn" class="bg-green-600 hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-bold py-6 px-12 rounded-xl text-2xl shadow-xl transition-all duration-200 transform hover:scale-105 disabled:transform-none" disabled>
                                CLOCK IN
                            </button>
                        @elseif(!$attendance->hasCheckedOut())
                            <button id="clock-out-btn" class="bg-red-600 hover:bg-red-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white font-bold py-6 px-12 rounded-xl text-2xl shadow-xl transition-all duration-200 transform hover:scale-105 disabled:transform-none" disabled>
                                CLOCK OUT
                            </button>
                        @else
                            <div class="text-center bg-green-50 p-8 rounded-xl border-2 border-green-200">
                                <div class="text-green-700 font-bold text-2xl mb-2">Attendance Complete for Today</div>
                                <div class="text-gray-600 text-lg">You have clocked in and out successfully.</div>
                            </div>
                        @endif
                    </div>

                    <!-- Status Messages -->
                    <div id="status-message" class="text-center hidden">
                        <div id="status-content" class="p-6 rounded-xl text-lg font-medium"></div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="mt-8 text-center">
                <a href="{{ route('attendance.index') }}" class="bg-blue-900 hover:bg-blue-800 text-white font-bold py-3 px-6 rounded-lg mr-4 inline-block transition-colors duration-200">
                    View Attendance Dashboard
                </a>
                <a href="{{ route('attendance.history') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg inline-block transition-colors duration-200">
                    View Attendance History
                </a>
            </div>
        </div>
    </div>

    <script>
        let camera = document.getElementById('camera');
        let canvas = document.getElementById('photo-canvas');
        let context = canvas.getContext('2d');
        let capturedPhotoData = null;

        // Update current time
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

        // Update time every second
        setInterval(updateTime, 1000);
        updateTime();

        // Camera functions
        document.getElementById('start-camera').addEventListener('click', async function() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        width: 400, 
                        height: 300,
                        facingMode: 'user' // Front camera for selfies
                    } 
                });
                camera.srcObject = stream;
                camera.style.display = 'block';
                document.getElementById('camera-placeholder').style.display = 'none';
                document.getElementById('start-camera').style.display = 'none';
                document.getElementById('capture-photo').style.display = 'inline-block';
            } catch (error) {
                alert('Camera access denied or not available: ' + error.message);
            }
        });

        document.getElementById('capture-photo').addEventListener('click', function() {
            // Draw video frame to canvas
            context.drawImage(camera, 0, 0, 400, 300);
            
            // Convert to base64
            capturedPhotoData = canvas.toDataURL('image/jpeg', 0.8);
            
            // Show preview
            document.getElementById('captured-photo').src = capturedPhotoData;
            document.getElementById('photo-preview').style.display = 'block';
            camera.style.display = 'none';
            document.getElementById('capture-photo').style.display = 'none';
            document.getElementById('retake-photo').style.display = 'inline-block';
            
            // Enable clock buttons
            enableClockButtons();
            
            // Stop camera stream
            let stream = camera.srcObject;
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        });

        document.getElementById('retake-photo').addEventListener('click', function() {
            document.getElementById('photo-preview').style.display = 'none';
            document.getElementById('retake-photo').style.display = 'none';
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

        // Clock In
        const clockInBtn = document.getElementById('clock-in-btn');
        if (clockInBtn) {
            clockInBtn.addEventListener('click', function() {
                if (!capturedPhotoData) {
                    alert('Please capture a photo first');
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
                })
                .catch(error => {
                    showStatusMessage('Error: ' + error.message, 'error');
                    clockInBtn.disabled = false;
                    clockInBtn.textContent = 'CLOCK IN';
                });
            });
        }

        // Clock Out
        const clockOutBtn = document.getElementById('clock-out-btn');
        if (clockOutBtn) {
            clockOutBtn.addEventListener('click', function() {
                if (!capturedPhotoData) {
                    alert('Please capture a photo first');
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
                })
                .catch(error => {
                    showStatusMessage('Error: ' + error.message, 'error');
                    clockOutBtn.disabled = false;
                    clockOutBtn.textContent = 'CLOCK OUT';
                });
            });
        }

        function showStatusMessage(message, type) {
            const statusDiv = document.getElementById('status-message');
            const contentDiv = document.getElementById('status-content');
            
            contentDiv.textContent = message;
            contentDiv.className = 'p-6 rounded-xl text-lg font-medium ' + 
                (type === 'success' ? 'bg-green-100 text-green-800 border-2 border-green-200' : 'bg-red-100 text-red-800 border-2 border-red-200');
            statusDiv.classList.remove('hidden');
            
            setTimeout(() => {
                statusDiv.classList.add('hidden');
            }, 5000);
        }
    </script>
</x-app-layout>