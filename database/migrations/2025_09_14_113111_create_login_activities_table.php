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
        Schema::create('login_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Login details
            $table->timestamp('login_at');
            $table->timestamp('logout_at')->nullable();
            $table->string('ip_address', 45); // Support IPv6
            $table->text('user_agent');
            
            // Device information
            $table->string('device_type')->nullable(); // Mobile, Desktop, Tablet
            $table->string('browser')->nullable();
            $table->string('platform')->nullable(); // Windows, macOS, Android, iOS
            $table->string('device_fingerprint')->nullable();
            
            // Session information
            $table->string('session_id')->nullable();
            $table->string('remember_option')->nullable(); // short, medium, long
            $table->boolean('device_trusted')->default(false);
            $table->integer('session_duration_minutes')->nullable();
            
            // Security flags
            $table->boolean('is_suspicious')->default(false);
            $table->string('logout_reason')->nullable(); // manual, timeout, forced
            $table->json('additional_data')->nullable(); // For future use
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index('user_id');
            $table->index('login_at');
            $table->index('ip_address');
            $table->index(['user_id', 'login_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_activities');
    }
};