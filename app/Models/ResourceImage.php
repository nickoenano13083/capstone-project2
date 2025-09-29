<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_id',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
