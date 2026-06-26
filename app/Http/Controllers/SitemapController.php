<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\PageSetting;
use App\Models\DirectoryItem;
use App\Models\News;
use App\Models\Event;
use Carbon\Carbon;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = Sitemap::create();
        
        // Calculate the maximum updated_at across all content models
        $lastmod = collect([
            PageSetting::max('updated_at'),
            DirectoryItem::max('updated_at'),
            News::max('updated_at'),
            Event::max('updated_at')
        ])->filter()->max();
        
        $lastmod = $lastmod ? Carbon::parse($lastmod) : Carbon::now();

        // 1. Home (Priority 1.0, Daily)
        $sitemap->add(Url::create(route('home.it'))->setLastModificationDate($lastmod)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)->setPriority(1.0));
        $sitemap->add(Url::create(route('home.en'))->setLastModificationDate($lastmod)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)->setPriority(1.0));

        // 2. Eventi (Priority 0.9, Daily)
        $sitemap->add(Url::create(route('events.it'))->setLastModificationDate($lastmod)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)->setPriority(0.9));
        $sitemap->add(Url::create(route('events.en'))->setLastModificationDate($lastmod)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)->setPriority(0.9));

        // 3. Notizie (Priority 0.8, Weekly)
        $sitemap->add(Url::create(route('news.it'))->setLastModificationDate($lastmod)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)->setPriority(0.8));
        $sitemap->add(Url::create(route('news.en'))->setLastModificationDate($lastmod)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)->setPriority(0.8));

        // 4. Scopri & Vivi / Esperienze (Priority 0.8, Monthly)
        $sitemap->add(Url::create(route('discover.it'))->setLastModificationDate($lastmod)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)->setPriority(0.8));
        $sitemap->add(Url::create(route('discover.en'))->setLastModificationDate($lastmod)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)->setPriority(0.8));

        // 5. Eccellenze (Priority 0.8, Monthly)
        $sitemap->add(Url::create(route('excellences.it'))->setLastModificationDate($lastmod)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)->setPriority(0.8));
        $sitemap->add(Url::create(route('excellences.en'))->setLastModificationDate($lastmod)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)->setPriority(0.8));

        // 6. Sapori (Priority 0.8, Monthly)
        $sitemap->add(Url::create(route('tastes.it'))->setLastModificationDate($lastmod)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)->setPriority(0.8));
        $sitemap->add(Url::create(route('tastes.en'))->setLastModificationDate($lastmod)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)->setPriority(0.8));

        // 7. Altre pagine (Priority 0.7, Monthly)
        $pages = ['community', 'gallery', 'proLoco', 'photo-thanks', 'privacy', 'cookie'];
        foreach ($pages as $page) {
            $sitemap->add(Url::create(route($page . '.it'))->setLastModificationDate($lastmod)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)->setPriority(0.7));
            $sitemap->add(Url::create(route($page . '.en'))->setLastModificationDate($lastmod)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)->setPriority(0.7));
        }

        return $sitemap->toResponse(request());
    }
}
