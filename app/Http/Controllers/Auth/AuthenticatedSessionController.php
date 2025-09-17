<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Carbon\Carbon;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request with enhanced session management.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = auth()->user();
        
        // Handle remember me options and device trust
        $this->handleRememberOptions($request, $user);
        
        // Set role-based session lifetime
        $this->setRoleBasedSessionLifetime($user);
        
        // Track login activity
        $this->trackLoginActivity($request, $user);

        // Redirect based on user role
        return $this->redirectByRole($user);
    }

    /**
     * Handle different remember me options
     */
    private function handleRememberOptions(LoginRequest $request, $user): void
    {
        $rememberOption = $request->input('remember_option', 'long');
        $trustDevice = $request->boolean('trust_device');
        
        // Set remember token duration based on selection
        $rememberDuration = $this->getRememberDuration($rememberOption);
        
        if ($request->boolean('remember')) {
            // Create custom remember token with specified duration
            $this->createRememberToken($user, $rememberDuration);
        }
        
        // Handle device trust
        if ($trustDevice) {
            $this->trustCurrentDevice($request, $user);
        }
        
        // Store session preferences
        session([
            'remember_option' => $rememberOption,
            'device_trusted' => $trustDevice,
            'login_time' => now(),
        ]);
    }

    /**
     * Set role-based session lifetime
     */
    private function setRoleBasedSessionLifetime($user): void
    {
        $roleLifetimes = config('session.role_lifetime', []);
        $userRole = $user->role ?? 'staff';
        
        if (isset($roleLifetimes[$userRole])) {
            $lifetime = $roleLifetimes[$userRole];
            
            // Set session lifetime in config for this request
            Config::set('session.lifetime', $lifetime);
            
            // Store role-based expiry in session data
            session([
                'role_session_lifetime' => $lifetime,
                'session_expires_at' => now()->addMinutes($lifetime),
                'user_role' => $userRole,
            ]);
            
            // Update user's session expiry in database
            $user->update([
                'session_expires_at' => now()->addMinutes($lifetime),
            ]);
            
            // Log the session configuration
            Log::info('Role-based session configured', [
                'user_id' => $user->id,
                'role' => $userRole,
                'lifetime_minutes' => $lifetime,
                'expires_at' => now()->addMinutes($lifetime)
            ]);
        }
    }

    /**
     * Get remember duration based on option
     */
    private function getRememberDuration(string $option): int
    {
        return match($option) {
            'short' => 1440,      // 24 hours
            'medium' => 10080,    // 7 days
            'long' => config('session.remember_duration', 40320), // 28 days
            default => config('session.remember_duration', 40320),
        };
    }

    /**
     * Create custom remember token
     */
    private function createRememberToken($user, int $duration): void
    {
        $token = hash('sha256', $user->getRememberTokenName() . '_' . now()->timestamp . '_' . $user->id);
        
        // Store token in user record with expiry
        $user->update([
            'remember_token' => $token,
            'remember_expires_at' => now()->addMinutes($duration),
        ]);
    }

    /**
     * Trust current device for faster future logins
     */
    private function trustCurrentDevice(Request $request, $user): void
    {
        $deviceFingerprint = $this->generateDeviceFingerprint($request);
        $deviceInfo = $this->getDeviceInfo($request);
        
        // Store trusted device
        $trustedDevices = $user->trusted_devices ?? [];
        
        $trustedDevices[] = [
            'fingerprint' => $deviceFingerprint,
            'device_info' => $deviceInfo,
            'trusted_at' => now(),
            'expires_at' => now()->addDays(90), // Trust for 90 days
            'ip_address' => $request->ip(),
        ];
        
        // Keep only last 5 trusted devices
        $trustedDevices = array_slice($trustedDevices, -5);
        
        $user->update(['trusted_devices' => $trustedDevices]);
        
        session(['current_device_trusted' => true]);
    }

    /**
     * Generate device fingerprint
     */
    private function generateDeviceFingerprint(Request $request): string
    {
        $userAgent = $request->userAgent() ?? '';
        $acceptLanguage = $request->header('Accept-Language', '');
        $acceptEncoding = $request->header('Accept-Encoding', '');
        
        return hash('sha256', $userAgent . $acceptLanguage . $acceptEncoding);
    }

    /**
     * Get device information
     */
    private function getDeviceInfo(Request $request): array
    {
        $userAgent = $request->userAgent() ?? '';
        
        // Basic device detection
        $isMobile = str_contains(strtolower($userAgent), 'mobile') || 
                   str_contains(strtolower($userAgent), 'android') ||
                   str_contains(strtolower($userAgent), 'iphone');
        
        $isTablet = str_contains(strtolower($userAgent), 'tablet') ||
                   str_contains(strtolower($userAgent), 'ipad');
        
        $deviceType = $isMobile ? 'Mobile' : ($isTablet ? 'Tablet' : 'Desktop');
        
        return [
            'user_agent' => $userAgent,
            'device_type' => $deviceType,
            'ip_address' => $request->ip(),
        ];
    }

    /**
     * Track login activity for security
     */
    private function trackLoginActivity(Request $request, $user): void
    {
        $loginData = [
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_at' => now(),
            'remember_option' => $request->input('remember_option'),
            'device_trusted' => $request->boolean('trust_device'),
        ];
        
        // Store in session for immediate use
        session(['last_login' => $loginData]);
        
        // Update user's last login info
        $user->updateLoginActivity([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        // Log for security monitoring
        Log::info('User login', $loginData);
    }

    /**
     * Redirect user based on role
     */
    private function redirectByRole($user): RedirectResponse
    {
        $redirectMessage = $this->getWelcomeMessage($user);
        
        $redirect = match($user->role) {
            'admin' => redirect()->route('dashboard.admin'),
            'hr' => redirect()->route('dashboard.hr'),
            'staff' => redirect()->route('dashboard.staff'),
            'intern' => redirect()->route('dashboard.intern'),
            'part_time' => redirect()->route('dashboard.parttime'),
            default => redirect()->route('dashboard'),
        };
        
        return $redirect->with('success', $redirectMessage);
    }

    /**
     * Get personalized welcome message
     */
    private function getWelcomeMessage($user): string
    {
        $timeOfDay = now()->format('H') < 12 ? 'Good morning' : 
                    (now()->format('H') < 17 ? 'Good afternoon' : 'Good evening');
        
        $sessionDuration = session('role_session_lifetime', config('session.lifetime'));
        $durationText = $this->formatSessionDuration($sessionDuration);
        
        return "{$timeOfDay}, {$user->name}! You're signed in for {$durationText}.";
    }

    /**
     * Format session duration for display
     */
    private function formatSessionDuration(int $minutes): string
    {
        if ($minutes >= 43200) return '30 days';
        if ($minutes >= 10080) return '1 week';   
        if ($minutes >= 1440) return '1 day';     
        if ($minutes >= 60) return round($minutes/60) . ' hours';
        return $minutes . ' minutes';
    }

    /**
     * Destroy an authenticated session with cleanup
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        // Log logout activity
        if ($user) {
            Log::info('User logout', [
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'session_duration' => session('login_time') ? 
                    now()->diffInMinutes(session('login_time')) . ' minutes' : 'Unknown'
            ]);
        }
        
        // Clear remember token if exists
        if ($user && $user->remember_token) {
            $user->update(['remember_token' => null, 'remember_expires_at' => null]);
        }
        
        Auth::guard('web')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Clear remember cookie
        Cookie::queue(Cookie::forget(Auth::getRecallerName()));
        
        return redirect('/')->with('success', 'You have been successfully logged out.');
    }
}