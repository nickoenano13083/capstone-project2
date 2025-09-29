<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Log a security-related event.
     *
     * @param string $action
     * @param string $description
     * @param array $metadata
     * @param int|null $userId
     * @return AuditLog
     */
    public static function log(string $action, string $description, array $metadata = [], ?int $userId = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'metadata' => $metadata,
        ]);
    }

    /**
     * Log a successful login event.
     *
     * @param int $userId
     * @param string $email
     * @return AuditLog
     */
    public static function loginSuccess(int $userId, string $email): AuditLog
    {
        return self::log(
            'login_success',
            "User {$email} logged in successfully",
            ['email' => $email],
            $userId
        );
    }

    /**
     * Log a failed login attempt.
     *
     * @param string $email
     * @param string $reason
     * @return AuditLog
     */
    public static function loginFailed(string $email, string $reason = 'Invalid credentials'): AuditLog
    {
        return self::log(
            'login_failed',
            "Failed login attempt for email: {$email}",
            [
                'email' => $email,
                'reason' => $reason,
                'attempts' => self::getLoginAttempts($email) + 1,
            ]
        );
    }

    /**
     * Log a password reset request.
     *
     * @param string $email
     * @return AuditLog
     */
    public static function passwordResetRequested(string $email): AuditLog
    {
        return self::log(
            'password_reset_requested',
            "Password reset requested for email: {$email}",
            ['email' => $email]
        );
    }

    /**
     * Log a successful password reset.
     *
     * @param int $userId
     * @param string $email
     * @return AuditLog
     */
    public static function passwordResetSuccess(int $userId, string $email): AuditLog
    {
        return self::log(
            'password_reset_success',
            "Password successfully reset for user: {$email}",
            ['email' => $email],
            $userId
        );
    }

    /**
     * Get the number of failed login attempts for an email.
     *
     * @param string $email
     * @return int
     */
    protected static function getLoginAttempts(string $email): int
    {
        return AuditLog::where('action', 'login_failed')
            ->where('metadata->email', $email)
            ->where('created_at', '>=', now()->subMinutes(30))
            ->count();
    }
}
