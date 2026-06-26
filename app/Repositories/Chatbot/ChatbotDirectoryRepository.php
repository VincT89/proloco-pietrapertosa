<?php

namespace App\Repositories\Chatbot;

use App\Models\DirectoryItem;
use Illuminate\Database\Eloquent\Collection;

class ChatbotDirectoryRepository
{
    public function getPlacesToVisit(): Collection
    {
        return DirectoryItem::where('category', 'scopri_luoghi')
            ->take(5)
            ->get();
    }

    public function getTypicalFood(): Collection
    {
        return DirectoryItem::where('category', 'sapori_piatti')
            ->take(5)
            ->get();
    }

    public function getExcellences(): Collection
    {
        return DirectoryItem::whereIn('category', ['eccellenze_aziende', 'eccellenze_foodtruck', 'eccellenze_artigiani'])
            ->take(5)
            ->get();
    }

    public function getCommunityItems(): Collection
    {
        return DirectoryItem::where('category', 'comunita')
            ->take(5)
            ->get();
    }

    public function searchDirectory(string $query, string $locale): Collection
    {
        return DirectoryItem::where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('title_en', 'LIKE', "%{$query}%")
                  ->orWhere('subtitle', 'LIKE', "%{$query}%")
                  ->orWhere('subtitle_en', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('description_en', 'LIKE', "%{$query}%");
            })
            ->take(5)
            ->get();
    }
}
