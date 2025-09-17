<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Employee Profile - {{ $employee->name }}
            </h2>
            <div class="space-x-2">
                @if(in_array(Auth::user()->role, ['admin', 'hr']) || Auth::user()->id == $employee->id)
                    <a href="{{ route('employees.edit', $employee) }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit Profile
                    </a>
                @endif
                
                @if(in_array(Auth::user()->role, ['admin', 'hr']))
                    <a href="{{ route('employees.index') }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Back to List
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Employee Profile Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Employee Header -->
                    <div class="flex items-center space-x-6 mb-8">
                        <!-- Profile Photo Placeholder -->
                        <div class="flex-shrink-0">
                            <div class="h-24 w-24 rounded-full bg-gray-300 flex items-center justify-center">
                                <span class="text-2xl font-medium text-gray-700">
                                    {{ substr($employee->name, 0, 2) }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Basic Info -->
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $employee->name }}</h1>
                            <p class="text-lg text-gray-600">{{ $employee->position ?? 'No Position' }}</p>
                            <p class="text-sm text-gray-500">{{ $employee->employee_id }}</p>
                            
                            <!-- Employment Status Badge -->
                            <div class="mt-2">
                                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                    @if($employee->employment_status == 'active') bg-green-100 text-green-800
                                    @elseif($employee->employment_status == 'blocked') bg-red-100 text-red-800
                                    @elseif($employee->employment_status == 'inactive') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($employee->employment_status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        @if(in_array(Auth::user()->role, ['admin', 'hr']) && Auth::user()->id !== $employee->id)
                            <div class="flex-shrink-0">
                                <form method="POST" action="{{ route('employees.toggle-status', $employee) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="px-4 py-2 rounded font-medium {{ $employee->employment_status == 'active' ? 'bg-red-100 text-red-800 hover:bg-red-200' : 'bg-green-100 text-green-800 hover:bg-green-200' }}"
                                            onclick="return confirm('Are you sure you want to {{ $employee->employment_status == 'active' ? 'block' : 'activate' }} this employee?')">
                                        {{ $employee->employment_status == 'active' ? 'Block Employee' : 'Activate Employee' }}
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    <!-- Employee Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Personal Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Personal Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $employee->email }}</dd>
                                </div>
                                
                                @if($employee->ic_number)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">IC Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $employee->ic_number }}</dd>
                                </div>
                                @endif
                                
                                @if($employee->phone)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $employee->phone }}</dd>
                                </div>
                                @endif
                                
                                @if($employee->date_of_birth)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $employee->date_of_birth->format('d M Y') }}</dd>
                                </div>
                                @endif
                                
                                @if($employee->gender)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Gender</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($employee->gender) }}</dd>
                                </div>
                                @endif
                                
                                @if($employee->address)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Address</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $employee->address }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        <!-- Employment Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Employment Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Role</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $employee->role)) }}</dd>
                                </div>
                                
                                @if($employee->department)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Department</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $employee->department }}</dd>
                                </div>
                                @endif
                                
                                @if($employee->hire_date)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Hire Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $employee->hire_date->format('d M Y') }}</dd>
                                </div>
                                @endif
                                
                                @if($employee->salary && in_array(Auth::user()->role, ['admin', 'hr']))
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Salary</dt>
                                    <dd class="mt-1 text-sm text-gray-900">RM {{ number_format($employee->salary, 2) }}</dd>
                                </div>
                                @endif
                                
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Employment Status</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($employee->employment_status) }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Emergency Contact Information -->
                    @if($employee->emergency_contact_name || $employee->emergency_contact_phone)
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Emergency Contact</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @if($employee->emergency_contact_name)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $employee->emergency_contact_name }}</dd>
                            </div>
                            @endif
                            
                            @if($employee->emergency_contact_relationship)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Relationship</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $employee->emergency_contact_relationship }}</dd>
                            </div>
                            @endif
                            
                            @if($employee->emergency_contact_phone)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $employee->emergency_contact_phone }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                    @endif

                    <!-- Account Information -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Account Information</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Account Created</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $employee->created_at->format('d M Y, g:i A') }}</dd>
                            </div>
                            
                            @if($employee->last_login_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Last Login</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $employee->last_login_at->format('d M Y, g:i A') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Enhanced Session Information (Admin/HR only) -->
                    @if(in_array(Auth::user()->role, ['admin', 'hr']))
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Session & Security Information</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @if($employee->last_login_ip)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Last Login IP</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $employee->last_login_ip }}</dd>
                            </div>
                            @endif
                            
                            @if($employee->session_expires_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Session Expires</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $employee->session_expires_at->format('d M Y, g:i A') }}</dd>
                            </div>
                            @endif
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Active Sessions</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $employee->active_sessions_count ?? 0 }}</dd>
                            </div>
                            
                            @if($employee->trusted_devices)
                            <div class="md:col-span-3">
                                <dt class="text-sm font-medium text-gray-500">Trusted Devices</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ count($employee->trusted_devices) }} device(s) trusted
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>