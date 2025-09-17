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
            // Enhanced remember token with expiration
            $table->timestamp('remember_expires_at')->nullable()->after('remember_token');
            
            // Trusted devices storage (JSON)
            $table->json('trusted_devices')->nullable()->after('remember_expires_at');
            
            // Last login tracking
            $table->timestamp('last_login_at')->nullable()->after('trusted_devices');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->text('last_login_user_agent')->nullable()->after('last_login_ip');
            
            // Security settings
            $table->boolean('two_factor_enabled')->default(false)->after('last_login_user_agent');
            $table->json('login_preferences')->nullable()->after('two_factor_enabled');
            
            // Session tracking
            $table->integer('active_sessions_count')->default(0)->after('login_preferences');
            $table->timestamp('session_expires_at')->nullable()->after('active_sessions_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'remember_expires_at',
                'trusted_devices',
                'last_login_at',
                'last_login_ip',
                'last_login_user_agent',
                'two_factor_enabled',
                'login_preferences',
                'active_sessions_count',
                'session_expires_at',
            ]);
        });
    }
};