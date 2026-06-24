<?php

namespace App\Http\Controllers;

use App\Models\DirectoryItem;
use App\Models\Event;
use App\Models\GalleryAlbum;
use App\Models\News;
use App\Models\PageSetting;
use Illuminate\Support\Facades\Cache;

class PublicController extends Controller
{
    public function home()
    {
        $page = PageSetting::with('heroMedia')->where('page_slug', 'home')->first();
        $events = Event::with('cover')
            ->where('status', 'published')
            ->orderByRaw('start_date IS NULL')
            ->orderBy('start_date', 'desc')
            ->take(3)
            ->get();
        $news = News::with('cover')->where('status', 'published')->orderBy('published_at', 'desc')->take(3)->get();

        return view('pages.home', compact('page', 'events', 'news'));
    }

    public function proLoco()
    {
        $page = PageSetting::with('heroMedia')->where('page_slug', 'pro-loco')->first();
        
        $financialDocuments = \App\Models\FinancialDocument::with('media')
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
        $realta = DirectoryItem::with('galleryMedia')->where('category', 'comunita')->get();

        return view('pages.community', compact('page', 'realta'));
    }

    public function territory()
    {
        $page = PageSetting::with('heroMedia')->where('page_slug', 'territorio')->first();
        $aziende = DirectoryItem::with('galleryMedia', 'externalMedia')->where('category', 'territorio_aziende')->get();
        $foodtruck = DirectoryItem::with('galleryMedia', 'externalMedia')->where('category', 'territorio_foodtruck')->get();
        $artigiani = DirectoryItem::with('galleryMedia', 'externalMedia')->where('category', 'territorio_artigiani')->get();

        return view('pages.territory', compact('page', 'aziende', 'foodtruck', 'artigiani'));
    }

    public function tastes()
    {
        $page = PageSetting::with('heroMedia')->where('page_slug', 'sapori')->first();
        $piatti = DirectoryItem::with('galleryMedia')->where('category', 'sapori_piatti')->get();

        return view('pages.tastes', compact('page', 'piatti'));
    }

    public function discover()
    {
        $page = PageSetting::with('heroMedia')->where('page_slug', 'scopri')->first();

        return view('pages.discover', compact('page'));
    }

    public function stories()
    {
        $page = PageSetting::with('heroMedia')->where('page_slug', 'storie')->first();

        return view('pages.stories', compact('page'));
    }

    public function borgo()
    {
        $page = PageSetting::with('heroMedia')->where('page_slug', 'il-borgo')->first();

        return view('pages.borgo', compact('page'));
    }

    public function contacts()
    {
        $page = PageSetting::with('heroMedia')->where('page_slug', 'contatti')->first();

        return view('pages.contacts', compact('page'));
    }

    public function news()
    {
        $page = PageSetting::with('heroMedia')->where('page_slug', 'notizie')->first();
        $news = News::with(['cover', 'attachmentsMedia', 'galleryMedia', 'externalMedia'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(9);

        return view('pages.news', compact('page', 'news'));
    }

    public function events()
    {
        $page = PageSetting::with('heroMedia')->where('page_slug', 'eventi')->first();
        $events = Event::with(['cover', 'galleryMedia', 'externalMedia'])
            ->where('status', 'published')
            ->orderByRaw('start_date IS NULL')
            ->orderBy('start_date', 'desc')
            ->paginate(9);
        $annualEvents = DirectoryItem::with('galleryMedia')
            ->where('category', 'eventi_annuali')
            ->orderBy('sort_order')
            ->get();

        return view('pages.events', compact('page', 'events', 'annualEvents'));
    }

    public function gallery()
    {
        $page = PageSetting::with('heroMedia')->where('page_slug', 'galleria')->first();
        $albums = GalleryAlbum::with(['galleryMedia', 'externalMedia'])
            ->orderByRaw('ISNULL(section_date), section_date DESC')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

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
