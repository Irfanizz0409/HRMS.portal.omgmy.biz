<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Employee - {{ $employee->name }}
            </h2>
            <a href="{{ route('employees.show', $employee) }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Cancel
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Validation Errors -->
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('employees.update', $employee) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Personal Information -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Personal Information</h3>
                                
                                <!-- Name -->
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $employee->name) }}" 
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                </div>

                                <!-- Email -->
                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $employee->email) }}" 
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                </div>

                                @if(in_array(Auth::user()->role, ['admin', 'hr']))
                                    <!-- IC Number -->
                                    <div class="mb-4">
                                        <label for="ic_number" class="block text-sm font-medium text-gray-700 mb-1">IC Number</label>
                                        <input type="text" name="ic_number" id="ic_number" value="{{ old('ic_number', $employee->ic_number) }}" 
                                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                @endif

                                <!-- Phone -->
                                <div class="mb-4">
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $employee->phone) }}" 
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <!-- Date of Birth -->
                                <div class="mb-4">
                                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                                    <input type="date" name="date_of_birth" id="date_of_birth" 
                                           value="{{ old('date_of_birth', $employee->date_of_birth?->format('Y-m-d')) }}" 
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <!-- Gender -->
                                <div class="mb-4">
                                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                                    <select name="gender" id="gender" 
                                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $employee->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $employee->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>

                                <!-- Address -->
                                <div class="mb-4">
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                    <textarea name="address" id="address" rows="3" 
                                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('address', $employee->address) }}</textarea>
                                </div>
                            </div>

                            <!-- Employment & Emergency Contact -->
                            <div>
                                @if(in_array(Auth::user()->role, ['admin', 'hr']))
                                    <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Employment Information</h3>
                                    
                                    <!-- Employee ID -->
                                    <div class="mb-4">
                                        <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-1">Employee ID *</label>
                                        <input type="text" name="employee_id" id="employee_id" value="{{ old('employee_id', $employee->employee_id) }}" 
                                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                    </div>

                                    <!-- Role -->
                                    <div class="mb-4">
                                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                                        <select name="role" id="role" 
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                            <option value="admin" {{ old('role', $employee->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="hr" {{ old('role', $employee->role) == 'hr' ? 'selected' : '' }}>HR</option>
                                            <option value="staff" {{ old('role', $employee->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                                            <option value="intern" {{ old('role', $employee->role) == 'intern' ? 'selected' : '' }}>Intern</option>
                                            <option value="part_time" {{ old('role', $employee->role) == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                        </select>
                                    </div>

                                    <!-- Department -->
                                    <div class="mb-4">
                                        <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                        <input type="text" name="department" id="department" value="{{ old('department', $employee->department) }}" 
                                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>

                                    <!-- Position -->
                                    <div class="mb-4">
                                        <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                                        <input type="text" name="position" id="position" value="{{ old('position', $employee->position) }}" 
                                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>

                                    <!-- Hire Date -->
                                    <div class="mb-4">
                                        <label for="hire_date" class="block text-sm font-medium text-gray-700 mb-1">Hire Date</label>
                                        <input type="date" name="hire_date" id="hire_date" 
                                               value="{{ old('hire_date', $employee->hire_date?->format('Y-m-d')) }}" 
                                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>

                                    <!-- Salary -->
                                    <div class="mb-4">
                                        <label for="salary" class="block text-sm font-medium text-gray-700 mb-1">Salary (RM)</label>
                                        <input type="number" name="salary" id="salary" step="0.01" 
                                               value="{{ old('salary', $employee->salary) }}" 
                                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>

                                    <!-- Employment Status -->
                                    <div class="mb-6">
                                        <label for="employment_status" class="block text-sm font-medium text-gray-700 mb-1">Employment Status *</label>
                                        <select name="employment_status" id="employment_status" 
                                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                            <option value="active" {{ old('employment_status', $employee->employment_status) == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('employment_status', $employee->employment_status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            <option value="blocked" {{ old('employment_status', $employee->employment_status) == 'blocked' ? 'selected' : '' }}>Blocked</option>
                                            <option value="terminated" {{ old('employment_status', $employee->employment_status) == 'terminated' ? 'selected' : '' }}>Terminated</option>
                                        </select>
                                    </div>
                                @endif

                                <h3 class="text-lg font-medium text-gray-900 mb-4 border-b pb-2">Emergency Contact</h3>
                                
                                <!-- Emergency Contact Name -->
                                <div class="mb-4">
                                    <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-1">Contact Name</label>
                                    <input type="text" name="emergency_contact_name" id="emergency_contact_name" 
                                           value="{{ old('emergency_contact_name', $employee->emergency_contact_name) }}" 
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <!-- Emergency Contact Relationship -->
                                <div class="mb-4">
                                    <label for="emergency_contact_relationship" class="block text-sm font-medium text-gray-700 mb-1">Relationship</label>
                                    <input type="text" name="emergency_contact_relationship" id="emergency_contact_relationship" 
                                           value="{{ old('emergency_contact_relationship', $employee->emergency_contact_relationship) }}" 
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <!-- Emergency Contact Phone -->
                                <div class="mb-4">
                                    <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 mb-1">Contact Phone</label>
                                    <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" 
                                           value="{{ old('emergency_contact_phone', $employee->emergency_contact_phone) }}" 
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="mt-8 pt-4 border-t border-gray-200">
                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('employees.show', $employee) }}" 
                                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Update Employee
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>