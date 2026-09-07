<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title', 'title_en', 'slug', 'description', 'description_en', 'start_date', 'end_date',
        'location', 'location_en', 'category', 'category_en', 'cover_media_id', 'status',
        'translation_status', 'seo_title', 'seo_title_en', 'seo_description', 'seo_description_en',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    public function scopeCurrentAndUpcoming(Builder $query): Builder
    {
        $today = today();

        return $query->where('status', 'published')
            ->where(fn (Builder $query) => $query
                ->where('end_date', '>=', $today)
                ->orWhere(fn (Builder $query) => $query
                    ->whereNull('end_date')->where('start_date', '>=', $today))
                ->orWhere(fn (Builder $query) => $query
                    ->whereNull('start_date')->whereNull('end_date')));
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

    public function externalMedia()
    {
        return $this->morphToMany(Media::class, 'mediable', 'mediables')
            ->withPivot('collection', 'order')
            ->wherePivot('collection', 'external_videos')
            ->orderByPivot('order');
    }
}
