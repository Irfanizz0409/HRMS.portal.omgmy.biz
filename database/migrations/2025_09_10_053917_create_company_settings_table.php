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
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->string('type')->default('string'); // string, integer, decimal, time, boolean
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default company work schedule settings
        DB::table('company_settings')->insert([
            [
                'key' => 'duty_start_time',
                'value' => '09:45:00',
                'type' => 'time',
                'description' => 'Time employees must arrive for duty',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'work_start_time', 
                'value' => '10:00:00',
                'type' => 'time',
                'description' => 'Official work start time',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'work_end_time',
                'value' => '20:00:00', 
                'type' => 'time',
                'description' => 'Official work end time',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'late_penalty_after',
                'value' => '09:46:00',
                'type' => 'time', 
                'description' => 'Late penalty starts after this time',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'break_duration_minutes',
                'value' => '60',
                'type' => 'integer',
                'description' => 'Lunch break duration in minutes',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'total_work_hours',
                'value' => '9.00',
                'type' => 'decimal',
                'description' => 'Standard working hours per day',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'ot_threshold_minutes',
                'value' => '30',
                'type' => 'integer',
                'description' => 'Minimum minutes for overtime calculation',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'ot_rounding_minutes',
                'value' => '15',
                'type' => 'integer', 
                'description' => 'Round overtime to nearest minutes',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};