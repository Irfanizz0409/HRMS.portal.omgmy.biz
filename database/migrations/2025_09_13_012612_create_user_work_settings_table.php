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
        Schema::create('user_work_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('required_hours_per_day', 4, 2)->default(8.00);
            $table->time('preferred_start_time')->default('10:00:00');
            $table->time('preferred_end_time')->default('18:00:00');
            $table->time('clock_in_deadline')->default('09:45:00');
            $table->boolean('flexible_timing')->default(false);
            $table->decimal('overtime_threshold', 4, 2)->default(8.00);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique('user_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_work_settings');
    }
};