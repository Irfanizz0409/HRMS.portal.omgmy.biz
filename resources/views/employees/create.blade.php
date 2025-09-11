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

                                <div class="mb-4">
                                    <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-1">Employee ID *</label>
                                    <input type="text" name="employee_id" id="employee_id" value="{{ old('employee_id') }}" 
                                           placeholder="EMP001" 
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
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
                                    <input type="text" name="department" id="department" value="{{ old('department') }}" 
                                           placeholder="e.g., Human Resources, IT, Operations" 
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div class="mb-4">
                                    <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                                    <input type="text" name="position" id="position" value="{{ old('position') }}" 
                                           placeholder="e.g., Manager, Executive, Assistant" 
                                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>

                                <div class="mb-4">
                                    <label for="hire_date" class="block text-sm font-medium text-gray-700 mb-1">Hire Date</label>
                                    <input type="date" name="hire_date" id="hire_date" value="{{ old('hire_date') }}" 
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

                        <div class="mt-8 pt-4 border-t border-gray-200">
                            <div class="bg-yellow-50 p-4 rounded-md mb-4">
                                <p class="text-sm text-yellow-800">
                                    <strong>Note:</strong> A temporary password will be generated for this employee. 
                                    They will need to complete their profile and change their password on first login.
                                </p>
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