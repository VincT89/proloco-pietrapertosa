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
            'eccellenze' => 'Eccellenze',
            'sapori' => 'Sapori',
            'scopri' => 'Scopri',
            'storie' => 'Storie',
            'il-borgo' => 'Il Borgo',
            'contatti' => 'Contatti',
            'notizie' => 'Notizie',
            'eventi' => 'Eventi',
            'galleria' => 'Galleria',
            'ringraziamenti-fotografici' => 'Ringraziamenti fotografici',
        ];
    }

    public static function ensureDefaultPages(): void
    {
        foreach (self::defaultPages() as $slug => $label) {
            $defaults = [
                'hero_title' => $label,
                'hero_title_en' => $label,
                'translation_status' => 'missing',
                'hero_overlay_opacity' => 0.4,
            ];

            if ($slug === 'ringraziamenti-fotografici') {
                $defaults['hero_title_en'] = 'Photo acknowledgements';
                $defaults['hero_subtitle'] = 'Un grazie a chi ha contribuito con immagini e materiali visivi.';
                $defaults['hero_subtitle_en'] = 'Thanks to those who contributed images and visual materials.';
            }

            self::firstOrCreate(
                ['page_slug' => $slug],
                $defaults
            );
        }
    }
}
