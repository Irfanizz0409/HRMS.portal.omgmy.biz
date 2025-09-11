<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Display employee listing (Admin & HR only)
     */
    public function index(Request $request)
    {
        // Check if user has permission to view employees
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'hr'])) {
            abort(403, 'Unauthorized access.');
        }

        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('employee_id', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by employment status
        if ($request->filled('status')) {
            $query->where('employment_status', $request->status);
        }

        $employees = $query->orderBy('employee_id')->paginate(10);

        // Get unique departments and roles for filter dropdowns
        $departments = User::whereNotNull('department')->distinct()->pluck('department');
        $roles = User::distinct()->pluck('role');

        return view('employees.index', compact('employees', 'departments', 'roles'));
    }

    /**
     * Show form to create new employee (Admin & HR only)
     */
    public function create()
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'hr'])) {
            abort(403, 'Unauthorized access.');
        }
        
        // Generate next employee ID
        $nextEmployeeId = $this->generateEmployeeId();
        
        return view('employees.create', compact('nextEmployeeId'));
    }

    /**
     * Store new employee
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'hr'])) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,hr,staff,intern,part_time',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric|min:0',
        ]);

        // Generate Employee ID automatically
        $validated['employee_id'] = $this->generateEmployeeId();

        // Generate temporary password
        $tempPassword = 'temp' . rand(1000, 9999);
        
        $validated['password'] = Hash::make($tempPassword);
        $validated['employment_status'] = 'active';

        $employee = User::create($validated);

        return redirect()->route('employees.index')
                        ->with('success', "Employee created successfully. Employee ID: {$employee->employee_id}, Temporary password: {$tempPassword}");
    }

    /**
     * Generate next employee ID in format OMG001, OMG002, etc.
     */
    private function generateEmployeeId()
    {
        // Get the latest employee ID
        $latestEmployee = User::whereNotNull('employee_id')
                            ->where('employee_id', 'like', 'OMG%')
                            ->orderBy('employee_id', 'desc')
                            ->first();

        if (!$latestEmployee) {
            // First employee
            return 'OMG001';
        }

        // Extract number from latest ID (OMG001 -> 001)
        $latestNumber = (int) substr($latestEmployee->employee_id, 3);
        
        // Increment and format with leading zeros
        $newNumber = $latestNumber + 1;
        
        return 'OMG' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Show employee profile
     */
    public function show(User $employee)
    {
        $user = Auth::user();
        
        // Admin and HR can view any employee
        // Other roles can only view their own profile
        if (!in_array($user->role, ['admin', 'hr']) && $user->id !== $employee->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('employees.show', compact('employee'));
    }

    /**
     * Show edit form for employee
     */
    public function edit(User $employee)
    {
        $user = Auth::user();
        
        // Admin and HR can edit any employee
        // Other roles can only edit their own profile (limited fields)
        if (!in_array($user->role, ['admin', 'hr']) && $user->id !== $employee->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('employees.edit', compact('employee'));
    }

    /**
     * Update employee information
     */
    public function update(Request $request, User $employee)
    {
        $user = Auth::user();
        
        // Check permissions
        if (!in_array($user->role, ['admin', 'hr']) && $user->id !== $employee->id) {
            abort(403, 'Unauthorized access.');
        }

        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ];

        // Admin and HR can edit additional fields
        if (in_array($user->role, ['admin', 'hr'])) {
            $rules = array_merge($rules, [
                'employee_id' => 'required|string|unique:users,employee_id,' . $employee->id,
                'ic_number' => 'nullable|string|max:20',
                'department' => 'nullable|string|max:255',
                'position' => 'nullable|string|max:255',
                'hire_date' => 'nullable|date',
                'salary' => 'nullable|numeric|min:0',
                'employment_status' => 'required|in:active,inactive,blocked,terminated',
                'role' => 'required|in:admin,hr,staff,intern,part_time',
            ]);
        }

        $validated = $request->validate($rules);

        // Update employee
        $employee->update($validated);

        return redirect()->route('employees.show', $employee)
                        ->with('success', 'Employee information updated successfully.');
    }

    /**
     * Toggle employee status (block/unblock)
     */
    public function toggleStatus(User $employee)
    {
        $user = Auth::user();
        
        // Only Admin and HR can toggle status
        if (!in_array($user->role, ['admin', 'hr'])) {
            abort(403, 'Unauthorized access.');
        }

        // Don't allow blocking yourself
        if ($user->id === $employee->id) {
            return back()->with('error', 'You cannot change your own status.');
        }

        // Toggle between active and blocked
        $newStatus = $employee->employment_status === 'active' ? 'blocked' : 'active';
        $employee->update(['employment_status' => $newStatus]);

        $action = $newStatus === 'blocked' ? 'blocked' : 'activated';
        return back()->with('success', "Employee has been {$action} successfully.");
    }

    /**
     * My profile (for current logged in user)
     */
    public function myProfile()
    {
        $employee = Auth::user();
        return view('employees.show', compact('employee'));
    }
}