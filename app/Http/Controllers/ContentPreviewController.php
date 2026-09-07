<?php

namespace App\Http\Controllers;

use App\Filament\Resources\Events\EventResource;
use App\Filament\Resources\News\NewsResource;
use App\Models\Event;
use App\Models\News;
use Filament\Facades\Filament;
use Illuminate\Http\Request;

class ContentPreviewController extends Controller
{
    public function show(Request $request, string $locale, string $type, int $record)
    {
        if (! $request->user()) {
            return redirect()->guest(route('filament.admin.auth.login'));
        }

        $panel = Filament::getPanel('admin');
        abort_unless($request->user()->canAccessPanel($panel), 403);
        Filament::setCurrentPanel($panel);
        app()->setLocale($locale);

        $item = $type === 'news' ? News::findOrFail($record) : Event::findOrFail($record);
        $resource = $type === 'news' ? NewsResource::class : EventResource::class;
        abort_unless($resource::canEdit($item), 403);
        $item->load(['cover', 'galleryMedia', 'externalMedia']);
        if ($item instanceof News) {
            $item->load('attachmentsMedia');
        }

        return response()->view('pages.content-detail', [
            'item' => $item,
            'kind' => $type,
            'isContentPreview' => true,
            'previewEditUrl' => $resource::getUrl('edit', ['record' => $item], panel: 'admin'),
        ])->header('X-Robots-Tag', 'noindex, nofollow')
            ->header('Cache-Control', 'private, no-store');
    }
}
