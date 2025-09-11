<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HRController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\InternController;
use App\Http\Controllers\PartTimeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Default dashboard (backup)
Route::get('/dashboard', function () {
    return view('dashboard');
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
    
    // Attendance Routes
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/clock', [AttendanceController::class, 'clockInOut'])->name('attendance.clock');
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');

    // Admin/HR Attendance Management
    Route::get('/attendance/admin', [AttendanceController::class, 'adminIndex'])->name('attendance.admin');
    Route::delete('/attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
    Route::put('/attendance/{attendance}', [AttendanceController::class, 'adminUpdate'])->name('attendance.admin-update');
    Route::get('/attendance/photo/{filename}', [AttendanceController::class, 'showPhoto'])->name('attendance.photo');
    
    // My Profile Route
    Route::get('/my-profile', [EmployeeController::class, 'myProfile'])->name('my-profile');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';