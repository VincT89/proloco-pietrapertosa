<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageSetting extends Model
{
    use \App\Traits\HasTranslations;

    protected $fillable = [
        'page_slug', 'hero_title', 'hero_title_en', 'hero_subtitle', 'hero_subtitle_en', 'hero_media_id',
        'intro_title', 'intro_title_en', 'intro_text', 'intro_text_en',
        'seo_title', 'seo_title_en', 'seo_description', 'seo_description_en',
        'translation_status',
    ];

    public function heroMedia()
    {
        return $this->belongsTo(Media::class, 'hero_media_id');
    }
}
