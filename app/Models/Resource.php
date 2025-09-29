<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type', // document, video, link, image, audio
        'file_path',
        'url',
        'file_size',
        'file_type',
        'uploaded_by',
        'chapter_id',
        'category',
        'status', // active, inactive, archived
        'download_count',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'download_count' => 'integer',
    ];

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFileSizeFormattedAttribute()
    {
        if (!$this->file_size) return 'N/A';
        
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getTypeIconAttribute()
    {
        return match($this->type) {
            'document' => 'fas fa-file-alt',
            'video' => 'fas fa-video',
            'link' => 'fas fa-link',
            'image' => 'fas fa-image',
            'audio' => 'fas fa-music',
            'pdf' => 'fas fa-file-pdf',
            'presentation' => 'fas fa-presentation',
            default => 'fas fa-file',
        };
    }

    public function getTypeColorAttribute()
    {
        return match($this->type) {
            'document' => 'text-blue-600',
            'video' => 'text-red-600',
            'link' => 'text-green-600',
            'image' => 'text-purple-600',
            'audio' => 'text-yellow-600',
            'pdf' => 'text-red-500',
            'presentation' => 'text-orange-600',
            default => 'text-gray-600',
        };
    }

    public function images(): HasMany
    {
        return $this->hasMany(ResourceImage::class);
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}
