<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use \App\Traits\HasTranslations;

    protected $fillable = [
        'title', 'title_en', 'slug', 'excerpt', 'excerpt_en', 'content', 'content_en', 
        'cover_media_id', 'status', 'translation_status', 'seo_title', 'seo_title_en', 
        'seo_description', 'seo_description_en', 'published_at'
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

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

    public function attachmentsMedia()
    {
        return $this->morphToMany(Media::class, 'mediable', 'mediables')
            ->withPivot('collection', 'order')
            ->wherePivot('collection', 'attachments')
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
