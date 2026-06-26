<?php

namespace App\Repositories\Chatbot;

use App\Models\PageSetting;
use Illuminate\Database\Eloquent\Collection;

class ChatbotPageRepository
{
    public function searchPages(string $query, string $locale): Collection
    {
        return PageSetting::where(function($q) use ($query) {
                $q->where('hero_title', 'LIKE', "%{$query}%")
                  ->orWhere('hero_title_en', 'LIKE', "%{$query}%")
                  ->orWhere('intro_title', 'LIKE', "%{$query}%")
                  ->orWhere('intro_title_en', 'LIKE', "%{$query}%")
                  ->orWhere('intro_text', 'LIKE', "%{$query}%")
                  ->orWhere('intro_text_en', 'LIKE', "%{$query}%");
            })
            ->take(5)
            ->get();
    }
}
