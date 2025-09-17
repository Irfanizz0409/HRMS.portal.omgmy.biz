<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HRController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\InternController;
use App\Http\Controllers\PartTimeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminWorkSettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Default dashboard - redirect to role-specific dashboard
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if (!$user) {
        return redirect()->route('login');
    }
    
    // Redirect based on user role
    switch ($user->role) {
        case 'admin':
            return redirect()->route('dashboard.admin');
        case 'hr':
            return redirect()->route('dashboard.hr');
        case 'staff':
            return redirect()->route('dashboard.staff');
        case 'intern':
            return redirect()->route('dashboard.intern');
        case 'part_time':
            return redirect()->route('dashboard.parttime');
        default:
            // Fallback for any unrecognized role
            return redirect()->route('dashboard.staff');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// 🔹 Role-based dashboards
Route::middleware('auth')->group(function () {
    Route::get('/dashboard/admin', [AdminController::class, 'dashboard'])->name('dashboard.admin');
    Route::get('/dashboard/hr', [HRController::class, 'dashboard'])->name('dashboard.hr');
    Route::get('/dashboard/staff', [StaffController::class, 'dashboard'])->name('dashboard.staff');
    Route::get('/dashboard/intern', [InternController::class, 'dashboard'])->name('dashboard.intern');
    Route::get('/dashboard/parttime', [PartTimeController::class, 'dashboard'])->name('dashboard.parttime');

    // Employee Management Routes
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::patch('/employees/{employee}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('employees.toggle-status');
    Route::get('/employees/{employee}/attendance', [EmployeeController::class, 'attendanceHistory'])->name('employees.attendance');
    
    // Attendance Routes
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/clock', [AttendanceController::class, 'clockInOut'])->name('attendance.clock');
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');
    Route::get('/attendance/{attendance}/photos', [AttendanceController::class, 'getPhotos'])->name('attendance.photos');
    

    // Admin/HR Attendance Management
    Route::get('/attendance/admin', [AttendanceController::class, 'adminIndex'])->name('attendance.admin');
    Route::delete('/attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
    Route::put('/attendance/{attendance}', [AttendanceController::class, 'adminUpdate'])->name('attendance.admin-update');
    Route::get('/attendance/photo/{filename}', [AttendanceController::class, 'showPhoto'])->name('attendance.photo');
    Route::get('/attendance/{attendance}/photos', [AttendanceController::class, 'getPhotos'])->name('attendance.photos');
    
    // Admin Work Settings Routes (NEW)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/work-settings', [AdminWorkSettingsController::class, 'index'])->name('work-settings.index');
        Route::get('/work-settings/{user}/edit', [AdminWorkSettingsController::class, 'edit'])->name('work-settings.edit');
        Route::put('/work-settings/{user}', [AdminWorkSettingsController::class, 'update'])->name('work-settings.update');
        Route::post('/work-settings/bulk-update', [AdminWorkSettingsController::class, 'bulkUpdate'])->name('work-settings.bulk-update');
        Route::patch('/work-settings/{user}/reset', [AdminWorkSettingsController::class, 'reset'])->name('work-settings.reset');
    });
    
    // My Profile Route
    Route::get('/my-profile', [EmployeeController::class, 'myProfile'])->name('my-profile');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';