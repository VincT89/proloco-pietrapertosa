<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageSetting extends Model
{
    use \App\Traits\HasTranslations;

    protected $fillable = [
        'page_slug', 'hero_title', 'hero_title_en', 'hero_subtitle', 'hero_subtitle_en', 'hero_media_id',
        'hero_cta_text', 'hero_cta_text_en', 'hero_cta_url', 'hero_overlay_opacity',
        'intro_title', 'intro_title_en', 'intro_text', 'intro_text_en',
        'seo_title', 'seo_title_en', 'seo_description', 'seo_description_en',
        'translation_status', 'data'
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function heroMedia()
    {
        return $this->belongsTo(Media::class, 'hero_media_id');
    }

    public static function defaultPages(): array
    {
        return [
            'home' => 'Home',
            'pro-loco' => 'Pro Loco',
            'comunita' => 'Comunità',
            'territorio' => 'Territorio',
            'sapori' => 'Sapori',
            'scopri' => 'Scopri',
            'storie' => 'Storie',
            'il-borgo' => 'Il Borgo',
            'contatti' => 'Contatti',
            'notizie' => 'Notizie',
            'eventi' => 'Eventi',
            'galleria' => 'Galleria',
        ];
    }

    public static function ensureDefaultPages(): void
    {
        foreach (self::defaultPages() as $slug => $label) {
            self::firstOrCreate(
                ['page_slug' => $slug],
                [
                    'hero_title' => $label,
                    'hero_title_en' => $label,
                    'translation_status' => 'missing',
                    'hero_overlay_opacity' => 0.4,
                ]
            );
        }
    }
}
