<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'member_code',
        'name',
        'email',
        'phone',
        'address',
        'join_date',
        'status',
        'chapter_id',
        'birthday',
        'age',
        'gender',
        'role',
        'is_archived',
    ];

    protected $casts = [
        'join_date' => 'date',
        'birthday' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($member) {
            // Ensure member_code is set (sequential per registration year: YYYY-######)
            if (empty($member->member_code)) {
                $year = null;
                if (!empty($member->join_date)) {
                    $year = $member->join_date->format('Y');
                }
                if (!$year && !empty($member->created_at)) {
                    $year = $member->created_at->format('Y');
                }
                if (!$year) {
                    $year = date('Y');
                }

                $prefix = $year . '-';
                $lastForYear = static::whereNotNull('member_code')
                    ->where('member_code', 'like', $prefix . '%')
                    ->orderByDesc('id')
                    ->value('member_code');

                $next = 1;
                if ($lastForYear && preg_match('/^' . preg_quote($year, '/') . '-(\d{6})$/', $lastForYear, $m)) {
                    $next = intval($m[1]) + 1;
                }
                $member->member_code = $year . '-' . str_pad((string)$next, 6, '0', STR_PAD_LEFT);
            }
            if (empty($member->qr_code)) {
                $member->qr_code = (string) Str::uuid();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function prayerRequests(): HasMany
    {
        return $this->hasMany(PrayerRequest::class);
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function ledChapters(): HasMany
    {
        return $this->hasMany(Chapter::class, 'leader_id');
    }

    /**
     * Get the QR code URL for this member
     */
    public function getQrCodeUrlAttribute()
    {
        return url('qr/scan/' . $this->qr_code);
    }

    /**
     * Get the member's age based on their birthday
     * 
     * @return int|null
     */
    public function getAgeAttribute()
    {
        if (empty($this->birthday)) {
            return null;
        }
        
        return $this->birthday->age;
    }

    /**
     * Get the age category of the member based on their birthday
     * 
     * @return string
     */
    public function getAgeCategoryAttribute()
    {
        $age = $this->age;
        
        if (is_null($age)) {
            return 'N/A';
        }

        // Age categories
        if ($age >= 60) return 'Senior';
        if ($age >= 26) return 'Adult';
        if ($age >= 13) return 'Youth';
        if ($age >= 3) return 'Kid';
        
        return 'Toddler'; // For ages 0-2
    }
}
