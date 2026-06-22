<?php

namespace App\Models;

use App\Services\CloudinaryService;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'type', 'provider', 'public_id', 'url', 'source_url', 'embed_url', 
        'thumbnail_url', 'duration', 'metadata', 'resource_type', 'alt', 
        'caption', 'folder'
    ];

    protected $casts = [
        'metadata' => 'json',
    ];
}
