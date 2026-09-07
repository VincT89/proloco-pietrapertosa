<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GalleryAlbum extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title', 'title_en', 'section_date', 'sort_order', 'translation_status',
    ];

    protected $casts = [
        'section_date' => 'date',
    ];

    public function scopeOrderedForDisplay(Builder $query): Builder
    {
        return $query->orderBy('sort_order')
            ->orderByRaw('CASE WHEN section_date IS NULL THEN 1 ELSE 0 END')
            ->orderBy('section_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc');
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
