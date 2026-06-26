<?php

namespace App\Repositories\Chatbot;

use App\Models\News;
use Illuminate\Database\Eloquent\Collection;

class ChatbotNewsRepository
{
    public function getLatestNews(): Collection
    {
        return News::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();
    }

    public function searchNews(string $query, string $locale): Collection
    {
        // Simple fallback search
        return News::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('title_en', 'LIKE', "%{$query}%")
                  ->orWhere('excerpt', 'LIKE', "%{$query}%")
                  ->orWhere('excerpt_en', 'LIKE', "%{$query}%")
                  ->orWhere('content', 'LIKE', "%{$query}%")
                  ->orWhere('content_en', 'LIKE', "%{$query}%");
            })
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();
    }
}
