<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Personal Information
            $table->string('employee_id')->nullable()->unique()->after('id');
            $table->string('ic_number')->nullable()->after('email');
            $table->string('phone')->nullable()->after('ic_number');
            $table->text('address')->nullable()->after('phone');
            $table->date('date_of_birth')->nullable()->after('address');
            $table->enum('gender', ['male', 'female'])->nullable()->after('date_of_birth');
            
            // Employment Details
            $table->string('department')->nullable()->after('role');
            $table->string('position')->nullable()->after('department');
            $table->date('hire_date')->nullable()->after('position');
            $table->decimal('salary', 10, 2)->nullable()->after('hire_date');
            $table->enum('employment_status', ['active', 'inactive', 'blocked', 'terminated'])->default('active')->after('salary');
            
            // Emergency Contact
            $table->string('emergency_contact_name')->nullable()->after('employment_status');
            $table->string('emergency_contact_relationship')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_relationship');
            
            // Profile & System
            $table->string('profile_photo')->nullable()->after('emergency_contact_phone');
            $table->timestamp('last_login')->nullable()->after('profile_photo');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'employee_id',
                'ic_number', 
                'phone',
                'address',
                'date_of_birth',
                'gender',
                'department',
                'position', 
                'hire_date',
                'salary',
                'employment_status',
                'emergency_contact_name',
                'emergency_contact_relationship', 
                'emergency_contact_phone',
                'profile_photo',
                'last_login'
            ]);
        });
    }
};