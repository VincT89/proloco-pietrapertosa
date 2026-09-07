<?php

namespace App\Http\Controllers;

use App\Models\DirectoryItem;
use App\Models\Event;
use App\Models\FinancialDocument;
use App\Models\GalleryAlbum;
use App\Models\News;
use App\Models\PageSetting;
use Carbon\Carbon;

class PublicController extends Controller
{
    public function home()
    {
        $page = PageSetting::with('heroMedia')->where('page_slug', 'home')->first();

        $events = Event::with('cover')
            ->currentAndUpcoming()
            ->orderByRaw('CASE WHEN start_date IS NULL THEN 1 ELSE 0 END')
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();
        $news = News::with('cover')->visibleToPublic()->orderBy('published_at', 'desc')->take(3)->get();

        return view('pages.home', compact('page', 'events', 'news'));
    }

    public function proLoco()
    {
        $page = PageSetting::with('heroMedia')->where('page_slug', 'pro-loco')->first();

        $financialDocuments = FinancialDocument::with('media')
            ->where('is_published', true)
            ->whereNotNull('media_id')
            ->orderBy('year', 'desc')
            ->orderBy('sort_order', 'asc')
            ->get()
            ->groupBy('year');

        return view('pages.proLoco', compact('page', 'financialDocuments'));
    }

    public function community()
    {
        $page = PageSetting::with('heroMedia')->where('page_slug', 'comunita')->first();
        $realta = DirectoryItem::with('galleryMedia')->where('category', 'comunita')->orderBy('sort_order')->orderBy('id')->get();

        return view('pages.community', compact('page', 'realta'));
    }

    public function excellences()
    {
        $page = PageSetting::with('heroMedia')->where('page_slug', 'eccellenze')->first();
        $aziende = DirectoryItem::with(['galleryMedia', 'externalMedia'])->where('category', 'eccellenze_aziende')->orderBy('sort_order')->get();
        $foodtruck = DirectoryItem::with(['galleryMedia', 'externalMedia'])->where('category', 'eccellenze_foodtruck')->orderBy('sort_order')->get();
        $artigiani = DirectoryItem::with(['galleryMedia', 'externalMedia'])->where('category', 'eccellenze_artigiani')->orderBy('sort_order')->get();

        return view('pages.excellences', compact('page', 'aziende', 'foodtruck', 'artigiani'));
    }

    public function tastes()
    {
        $page = PageSetting::with('heroMedia')->where('page_slug', 'sapori')->first();
        $piatti = DirectoryItem::with('galleryMedia')->where('category', 'sapori_piatti')->orderBy('sort_order')->orderBy('id')->get();

        return view('pages.tastes', compact('page', 'piatti'));
    }

    public function discover()
    {
        $page = PageSetting::with('heroMedia')->where('page_slug', 'scopri')->first();

        $luoghi = DirectoryItem::with(['galleryMedia', 'externalMedia'])
            ->where('category', 'scopri_luoghi')
            ->orderBy('sort_order')
            ->get();

        return view('pages.discover', compact('page', 'luoghi'));
    }

    public function news()
    {
        $page = PageSetting::with('heroMedia')->where('page_slug', 'notizie')->first();
        $news = News::with('cover')
            ->visibleToPublic()
            ->orderBy('published_at', 'desc')
            ->paginate(9)->withQueryString()->fragment('news-list');

        return view('pages.news', compact('page', 'news'));
    }

    public function newsShow(News $news)
    {
        abort_unless($news->isVisibleToPublic(), 404);
        $news->load(['cover', 'attachmentsMedia', 'galleryMedia', 'externalMedia']);

        return view('pages.content-detail', ['item' => $news, 'kind' => 'news']);
    }

    public function eventShow(Event $event)
    {
        abort_unless($event->status === 'published', 404);
        $event->load(['cover', 'galleryMedia', 'externalMedia']);

        return view('pages.content-detail', ['item' => $event, 'kind' => 'event']);
    }

    public function traditionShow(DirectoryItem $tradition)
    {
        abort_unless($tradition->category === 'eventi_annuali', 404);
        $tradition->load(['galleryMedia', 'externalMedia']);

        return view('pages.content-detail', ['item' => $tradition, 'kind' => 'tradition']);
    }

    public function events()
    {
        $page = PageSetting::with('heroMedia')->where('page_slug', 'eventi')->first();

        $cutoff = Carbon::today();

        $events = Event::with('cover')
            ->currentAndUpcoming()
            ->orderByRaw('CASE WHEN start_date IS NULL THEN 1 ELSE 0 END')
            ->orderBy('start_date')
            ->paginate(9)->withQueryString()->fragment('upcoming-title');

        $pastEvents = Event::where('status', 'published')
            ->where(function ($query) use ($cutoff) {
                $query->where('end_date', '<', $cutoff)
                    ->orWhere(fn ($query) => $query->whereNull('end_date')->where('start_date', '<', $cutoff));
            })
            ->orderBy('start_date', 'desc')
            ->paginate(12, ['*'], 'archive')->withQueryString()->fragment('past-events-title');

        $annualEvents = DirectoryItem::with('galleryMedia')
            ->where('category', 'eventi_annuali')->orderBy('sort_order')->get();

        return view('pages.events', compact('page', 'events', 'annualEvents', 'pastEvents'));
    }

    public function gallery()
    {
        $page = PageSetting::with('heroMedia')->where('page_slug', 'galleria')->first();
        $albums = GalleryAlbum::with(['galleryMedia', 'externalMedia'])
            ->orderedForDisplay()
            ->paginate(12)->withQueryString()->fragment('gallery-list');

        return view('pages.gallery', compact('page', 'albums'));
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function cookie()
    {
        return view('pages.cookie');
    }

    public function photoThanks()
    {
        $page = PageSetting::with('heroMedia')->where('page_slug', 'ringraziamenti-fotografici')
            ->with('heroMedia')
            ->firstOrFail();

        return view('pages.photo-thanks', compact('page'));
    }
}
