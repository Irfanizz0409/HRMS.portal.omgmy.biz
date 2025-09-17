<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Add New Employee
            </h2>
            <a href="{{ route('employees.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('employees.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Basic Information</h3>
                                
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                </div>

                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                </div>

                                <!-- Auto-generated Employee ID (Read-only) -->
                                <div class="mb-4">
                                    <label for="employee_id_display" class="block text-sm font-medium text-gray-700 mb-1">Employee ID (Auto-generated)</label>
                                    <input type="text" id="employee_id_display" 
                                           value="{{ $nextEmployeeId }}" 
                                           class="w-full border-gray-300 rounded-md shadow-sm bg-gray-100 text-gray-600 cursor-not-allowed" 
                                           readonly disabled>
                                    <p class="text-xs text-gray-500 mt-1">Employee ID will be automatically assigned: {{ $nextEmployeeId }}</p>
                                </div>

                                <div class="mb-4">
                                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                                    <select name="role" id="role" 
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                        <option value="">Select Role</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="hr" {{ old('role') == 'hr' ? 'selected' : '' }}>HR</option>
                                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                        <option value="intern" {{ old('role') == 'intern' ? 'selected' : '' }}>Intern</option>
                                        <option value="part_time" {{ old('role') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Employment Details -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Employment Details</h3>
                                
                                <div class="mb-4">
                                    <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                    <select name="department" id="department" 
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Select Department</option>
                                        <option value="Human Resources" {{ old('department') == 'Human Resources' ? 'selected' : '' }}>Human Resources</option>
                                        <option value="Information Technology" {{ old('department') == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                                        <option value="Operations" {{ old('department') == 'Operations' ? 'selected' : '' }}>Operations</option>
                                        <option value="Finance" {{ old('department') == 'Finance' ? 'selected' : '' }}>Finance</option>
                                        <option value="Marketing" {{ old('department') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                        <option value="Sales" {{ old('department') == 'Sales' ? 'selected' : '' }}>Sales</option>
                                        <option value="Customer Service" {{ old('department') == 'Customer Service' ? 'selected' : '' }}>Customer Service</option>
                                        <option value="Administration" {{ old('department') == 'Administration' ? 'selected' : '' }}>Administration</option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                                    <input type="text" name="position" id="position" value="{{ old('position') }}" 
                                           placeholder="e.g., Manager, Executive, Assistant" 
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div class="mb-4">
                                    <label for="hire_date" class="block text-sm font-medium text-gray-700 mb-1">Hire Date</label>
                                    <input type="date" name="hire_date" id="hire_date" 
                                           value="{{ old('hire_date', date('Y-m-d')) }}" 
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div class="mb-4">
                                    <label for="salary" class="block text-sm font-medium text-gray-700 mb-1">Salary (RM)</label>
                                    <input type="number" name="salary" id="salary" step="0.01" value="{{ old('salary') }}" 
                                           placeholder="0.00" 
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information Section -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Preview</h3>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-blue-800">Next Employee ID:</span>
                                        <span class="text-blue-600 font-bold">{{ $nextEmployeeId }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-blue-800">Temporary Password:</span>
                                        <span class="text-blue-600 font-mono">Will be auto-generated</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-4 border-t border-gray-200">
                            <div class="bg-yellow-50 p-4 rounded-md mb-4">
                                <p class="text-sm text-yellow-800">
                                    <strong>Important Notes:</strong>
                                </p>
                                <ul class="text-sm text-yellow-700 mt-2 list-disc list-inside space-y-1">
                                    <li>Employee ID <strong>{{ $nextEmployeeId }}</strong> will be automatically assigned</li>
                                    <li>A temporary password will be generated and displayed after creation</li>
                                    <li>Employee will need to complete their profile on first login</li>
                                    <li>Default employment status will be set to "Active"</li>
                                </ul>
                            </div>
                            
                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('employees.index') }}" 
                                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Create Employee
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>