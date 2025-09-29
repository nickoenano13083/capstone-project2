<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'description',
        'leader_id',
        'leader_type',
        'status',
    ];

    public function leader(): BelongsTo
    {
        if ($this->leader_type === 'App\Models\User') {
            return $this->belongsTo(User::class, 'leader_id');
        }
        return $this->belongsTo(Member::class, 'leader_id');
    }

    public function memberLeader(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'leader_id');
    }

    public function userLeader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function members()
    {
        return $this->hasMany(\App\Models\Member::class);
    }
}
