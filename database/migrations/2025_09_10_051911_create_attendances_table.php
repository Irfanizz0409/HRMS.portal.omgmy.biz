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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('clock_in_time')->nullable();
            $table->time('clock_out_time')->nullable();
            $table->string('clock_in_photo')->nullable();
            $table->string('clock_out_photo')->nullable();
            $table->text('clock_in_location')->nullable(); // GPS coordinates
            $table->text('clock_out_location')->nullable();
            $table->decimal('total_hours', 5, 2)->nullable(); // Working hours
            $table->enum('status', [
                'safe',                 // Clocked in before 9:45 AM
                'late',                 // Clocked in after 9:45 AM
                'complete',             // Safe + worked required hours + clocked out after 8 PM
                'late_complete',        // Late + worked required hours + clocked out after 8 PM
                'early_complete',       // Safe + worked required hours + clocked out before 8 PM
                'late_early_complete',  // Late + worked required hours + clocked out before 8 PM
                'incomplete',           // Missing clock in/out or insufficient hours
                'cross_day',            // Completed next day (forgot to clock out)
                'absent'                // No attendance record
            ])->default('incomplete');
            $table->text('notes')->nullable(); // Admin/HR notes
            $table->timestamps();

            // Ensure one attendance record per user per date
            $table->unique(['user_id', 'date']);
            
            // Index for faster queries
            $table->index('date');
            $table->index(['user_id', 'date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};