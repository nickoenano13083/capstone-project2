<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\QrCode; // Added this import
use App\Models\Chapter;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'date',
        'time',
        'end_time',
        'location',
        'status',
        'archived',
        'chapter_id',
    ];

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
    ];
    
    protected $casts = [
        'date' => 'date',
    ];

    public function attendance()
    {
        return $this->hasMany(\App\Models\Attendance::class);
    }

    public function qrCodes(): HasMany
    {
        return $this->hasMany(QrCode::class);
    }

    public function activeQrCode()
    {
        return $this->qrCodes()->where('is_active', true)->where(function($query) {
            $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
        })->first();
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    /**
     * Get the formatted date attribute.
     *
     * @return string
     */
    public function getFormattedDateAttribute()
    {
        return $this->date ? $this->date->format('M d, Y') : null;
    }
}
