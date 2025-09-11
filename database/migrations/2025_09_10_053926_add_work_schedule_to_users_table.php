<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Individual work schedule overrides (nullable = use company defaults)
            $table->time('custom_duty_start_time')->nullable()->after('profile_photo');
            $table->time('custom_work_start_time')->nullable()->after('custom_duty_start_time');
            $table->time('custom_work_end_time')->nullable()->after('custom_work_start_time');
            $table->decimal('custom_work_hours', 4, 2)->nullable()->after('custom_work_end_time');
            $table->integer('custom_break_minutes')->nullable()->after('custom_work_hours');
            
            // Flexible schedule for managers/special cases
            $table->boolean('is_flexible_schedule')->default(false)->after('custom_break_minutes');
            
            // Work schedule notes
            $table->text('schedule_notes')->nullable()->after('is_flexible_schedule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'custom_duty_start_time',
                'custom_work_start_time', 
                'custom_work_end_time',
                'custom_work_hours',
                'custom_break_minutes',
                'is_flexible_schedule',
                'schedule_notes'
            ]);
        });
    }
};