<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update any existing records to use new status values
        DB::table('attendances')->where('status', 'present')->update(['status' => 'safe']);
        
        // Drop the existing enum constraint and recreate with new values
        DB::statement("ALTER TABLE attendances MODIFY COLUMN status ENUM(
            'safe',
            'late', 
            'complete',
            'late_complete',
            'early_complete', 
            'late_early_complete',
            'incomplete',
            'cross_day',
            'absent'
        ) DEFAULT 'incomplete'");
        
        // Add index for better performance
        Schema::table('attendances', function (Blueprint $table) {
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        DB::statement("ALTER TABLE attendances MODIFY COLUMN status ENUM(
            'present',
            'late',
            'absent', 
            'incomplete'
        ) DEFAULT 'incomplete'");
        
        // Update records back to original values
        DB::table('attendances')->where('status', 'safe')->update(['status' => 'present']);
        DB::table('attendances')->whereIn('status', [
            'complete', 'late_complete', 'early_complete', 'late_early_complete', 'cross_day'
        ])->update(['status' => 'present']);
        
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
};