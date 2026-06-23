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

    public function optimizedUrl(string $preset = 'default'): ?string
    {
        return cloudinary_url($this->url, $preset);
    }

    public function isVideo(): bool
    {
        return $this->type === 'video' || $this->resource_type === 'video';
    }

    public function optimizedVideoUrl(): ?string
    {
        if (! $this->url || ! str_contains($this->url, '/video/upload/')) {
            return $this->url;
        }

        return str_replace('/video/upload/', '/video/upload/q_auto,f_auto/', $this->url);
    }

    public function videoThumbnailUrl(string $preset = 'card'): ?string
    {
        if (! $this->url || ! str_contains($this->url, '/video/upload/')) {
            return null;
        }

        $transform = match ($preset) {
            'thumb' => 'so_1,w_400,h_300,c_fill,f_jpg',
            'card' => 'so_1,w_600,h_400,c_fill,f_jpg',
            default => 'so_1,w_600,h_400,c_fill,f_jpg',
        };

        return str_replace('/video/upload/', '/video/upload/'.$transform.'/', $this->url);
    }

    public function isInUse(): bool
    {
        return \Illuminate\Support\Facades\DB::table('mediables')->where('media_id', $this->id)->exists()
            || \Illuminate\Support\Facades\DB::table('news')->where('cover_media_id', $this->id)->exists()
            || \Illuminate\Support\Facades\DB::table('events')->where('cover_media_id', $this->id)->exists()
            || \Illuminate\Support\Facades\DB::table('page_settings')->where('hero_media_id', $this->id)->exists()
            || \Illuminate\Support\Facades\DB::table('page_settings')->where('data', 'like', '%"img_media_id":' . $this->id . '%')->exists();
    }
}
