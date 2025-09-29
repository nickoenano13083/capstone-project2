<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrayerRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'user_id',
        'request',
        'status',
        'response',
        'prayer_date',
        'is_anonymous',
    ];

    protected $casts = [
        'prayer_date' => 'date',
        'is_anonymous' => 'boolean',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
