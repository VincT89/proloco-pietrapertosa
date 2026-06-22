<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DirectoryItem extends Model
{
    use \App\Traits\HasTranslations;

    protected $fillable = [
        'category', 'title', 'title_en', 'subtitle', 'subtitle_en', 'description', 'description_en',
        'contact_info', 'contact_info_en', 'stats', 'sort_order', 'translation_status'
    ];

    protected $casts = [
        'stats' => 'array',
    ];

    public function cover()
    {
        return $this->belongsTo(Media::class, 'cover_media_id');
    }

    public function galleryMedia()
    {
        return $this->morphToMany(Media::class, 'mediable', 'mediables')
            ->withPivot('collection', 'order')
            ->wherePivot('collection', 'gallery')
            ->orderByPivot('order');
    }

    public function externalMedia()
    {
        return $this->morphToMany(Media::class, 'mediable', 'mediables')
            ->withPivot('collection', 'order')
            ->wherePivot('collection', 'external_videos')
            ->orderByPivot('order');
    }
}
