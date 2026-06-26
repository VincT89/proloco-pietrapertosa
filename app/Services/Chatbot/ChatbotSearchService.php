<?php

namespace App\Services\Chatbot;

use App\Models\Event;
use App\Models\News;
use App\Models\DirectoryItem;
use App\Models\PageSetting;
use App\Models\FinancialDocument;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ChatbotSearchService
{
    public function getRouteMap(): array
    {
        return [
            'home' => 'home',
            'pro-loco' => 'proLoco',
            'contatti' => 'proLoco',
            'comunita' => 'community',
            'storie' => 'community',
            'eccellenze' => 'excellences',
            'sapori' => 'tastes',
            'scopri' => 'discover',
            'il-borgo' => 'discover',
            'notizie' => 'news',
            'eventi' => 'events',
            'galleria' => 'gallery',
            'ringraziamenti-fotografici' => 'photo-thanks'
        ];
    }

    public function normalizeQuery(string $query, string $locale, bool $useSynonyms = true): array
    {
        $query = mb_strtolower(trim($query));
        
        // Rimuove articoli apostrofati prima di rimuovere la punteggiatura
        $query = preg_replace("/\b(l'|dell'|all'|un'|sull'|nell')/u", "", $query);
        
        // Sostituisce apostrofi restanti e punteggiatura varia con spazi
        $query = preg_replace("/['’]/u", " ", $query);
        $query = preg_replace("/[^\p{L}\p{N}\s]/u", "", $query);
        
        $words = array_filter(explode(' ', $query));
        $stops = config('chatbot.stopwords.' . $locale, []);
        $synonyms = config('chatbot.synonyms', []);
        
        $terms = [];
        foreach ($words as $word) {
            $word = trim($word);
            if (strlen($word) > 1 && !in_array($word, $stops)) {
                $terms[] = $word;
                
                // Sinonimi
                if ($useSynonyms && isset($synonyms[$word])) {
                    $terms = array_merge($terms, $synonyms[$word]);
                }
            }
        }
        
        return array_values(array_unique($terms));
    }

    public function search(string $query, string $locale): Collection
    {
        $terms = $this->normalizeQuery($query, $locale);
        $results = collect();
        
        if (empty($terms)) {
            return $results;
        }

        $results = $results->merge($this->searchEvents($terms, $locale));
        $results = $results->merge($this->searchNews($terms, $locale));
        $results = $results->merge($this->searchDirectory($terms, $locale));
        $results = $results->merge($this->searchPages($terms, $locale));
        $results = $results->merge($this->searchDocuments($terms, $locale));

        // Group by URL to avoid duplicates from different queries matching the same item
        $uniqueResults = $results->groupBy('url')->map(function ($group) {
            // Keep the one with the highest score
            return $group->sortByDesc('score')->first();
        })->values();

        return $uniqueResults->sortByDesc('score')->take(5)->values();
    }

    public function findStrongTitleMatch(string $query, string $locale, bool $useSynonyms = true): bool
    {
        $terms = $this->normalizeQuery($query, $locale, $useSynonyms);
        if (empty($terms)) return false;

        $termLikes = collect($terms)->map(fn($t) => "%{$t}%")->toArray();

        // Check Directory
        $hasDirectory = DirectoryItem::where(function($q) use ($terms) {
                foreach ($terms as $term) {
                    $q->orWhere('title', 'LIKE', "%{$term}%")
                      ->orWhere('title_en', 'LIKE', "%{$term}%");
                }
            })->exists();
        if ($hasDirectory) return true;

        // Check Events
        $hasEvent = Event::where('status', 'published')
            ->where(function($q) use ($terms) {
                foreach ($terms as $term) {
                    $q->orWhere('title', 'LIKE', "%{$term}%")
                      ->orWhere('title_en', 'LIKE', "%{$term}%")
                      ->orWhere('slug', 'LIKE', "%{$term}%");
                }
            })->exists();
        if ($hasEvent) return true;

        return false;
    }

    protected function calculateScore(array $terms, array $fields): int
    {
        $score = 0;
        foreach ($terms as $term) {
            foreach ($fields as $field => $weight) {
                if (empty($field)) continue;
                if (mb_strpos(mb_strtolower($field), $term) !== false) {
                    $score += $weight;
                }
            }
        }
        return $score;
    }

    protected function searchEvents(array $terms, string $locale): Collection
    {
        $query = Event::where('status', 'published');
        
        $query->where(function($q) use ($terms) {
            foreach ($terms as $term) {
                $q->orWhere('title', 'LIKE', "%{$term}%")
                  ->orWhere('title_en', 'LIKE', "%{$term}%")
                  ->orWhere('description', 'LIKE', "%{$term}%")
                  ->orWhere('description_en', 'LIKE', "%{$term}%")
                  ->orWhere('seo_title', 'LIKE', "%{$term}%")
                  ->orWhere('seo_description', 'LIKE', "%{$term}%");
            }
        });

        $events = $query->get();
        return $events->map(function ($event) use ($terms, $locale) {
            $title = $locale === 'en' && $event->title_en ? $event->title_en : $event->title;
            $description = $locale === 'en' && $event->description_en ? $event->description_en : $event->description;
            
            $score = $this->calculateScore($terms, [
                $title => 100,
                $event->slug => 90,
                $description => 50,
                $event->seo_title => 20,
                $event->seo_description => 20
            ]);

            return [
                'type' => 'card',
                'title' => $title,
                'subtitle' => $locale === 'en' ? 'Event' : 'Evento',
                'description' => Str::limit(strip_tags($description), 80),
                'url' => route('events.' . $locale),
                'image' => $event->cover ? $event->cover->url : null,
                'score' => $score
            ];
        });
    }

    protected function searchNews(array $terms, string $locale): Collection
    {
        $query = News::where('status', 'published');
        
        $query->where(function($q) use ($terms) {
            foreach ($terms as $term) {
                $q->orWhere('title', 'LIKE', "%{$term}%")
                  ->orWhere('title_en', 'LIKE', "%{$term}%")
                  ->orWhere('excerpt', 'LIKE', "%{$term}%")
                  ->orWhere('excerpt_en', 'LIKE', "%{$term}%")
                  ->orWhere('content', 'LIKE', "%{$term}%")
                  ->orWhere('content_en', 'LIKE', "%{$term}%")
                  ->orWhere('seo_title', 'LIKE', "%{$term}%");
            }
        });

        $news = $query->get();
        return $news->map(function ($n) use ($terms, $locale) {
            $title = $locale === 'en' && $n->title_en ? $n->title_en : $n->title;
            $excerpt = $locale === 'en' && $n->excerpt_en ? $n->excerpt_en : $n->excerpt;
            $content = $locale === 'en' && $n->content_en ? $n->content_en : $n->content;
            
            $score = $this->calculateScore($terms, [
                $title => 100,
                $n->slug => 90,
                $excerpt => 70,
                $content => 50,
                $n->seo_title => 20
            ]);

            return [
                'type' => 'card',
                'title' => $title,
                'subtitle' => $locale === 'en' ? 'News' : 'Notizia',
                'description' => Str::limit(strip_tags($excerpt ?: $content), 80),
                'url' => route('news.' . $locale),
                'image' => $n->cover ? $n->cover->url : null,
                'score' => $score
            ];
        });
    }

    protected function searchDirectory(array $terms, string $locale): Collection
    {
        $query = DirectoryItem::query();
        
        $query->where(function($q) use ($terms) {
            foreach ($terms as $term) {
                $q->orWhere('title', 'LIKE', "%{$term}%")
                  ->orWhere('title_en', 'LIKE', "%{$term}%")
                  ->orWhere('subtitle', 'LIKE', "%{$term}%")
                  ->orWhere('subtitle_en', 'LIKE', "%{$term}%")
                  ->orWhere('description', 'LIKE', "%{$term}%")
                  ->orWhere('description_en', 'LIKE', "%{$term}%");
            }
        });

        $items = $query->get();
        return $items->map(function ($item) use ($terms, $locale) {
            $title = $locale === 'en' && $item->title_en ? $item->title_en : $item->title;
            $subtitle = $locale === 'en' && $item->subtitle_en ? $item->subtitle_en : $item->subtitle;
            $description = $locale === 'en' && $item->description_en ? $item->description_en : $item->description;
            
            $score = $this->calculateScore($terms, [
                $title => 100,
                $item->category => 90,
                $subtitle => 70,
                $description => 50
            ]);

            try {
                $url = match($item->category) {
                    'scopri_luoghi' => route('discover.' . $locale),
                    'scopri_esperienze' => route('discover.' . $locale),
                    'sapori_piatti' => route('tastes.' . $locale),
                    'eccellenze_aziende' => route('excellences.' . $locale),
                    'eccellenze_foodtruck' => route('excellences.' . $locale),
                    'eccellenze_artigiani' => route('excellences.' . $locale),
                    'comunita' => route('community.' . $locale),
                    default => route('home.' . $locale)
                };
            } catch (\Exception $e) {
                $url = route('home.' . $locale);
            }

            return [
                'type' => 'card',
                'title' => $title,
                'subtitle' => $subtitle ?: 'Directory',
                'description' => Str::limit(strip_tags($description), 80),
                'url' => $url,
                'image' => $item->cover ? $item->cover->url : null,
                'score' => $score
            ];
        });
    }

    protected function searchPages(array $terms, string $locale): Collection
    {
        $query = PageSetting::query();
        
        $query->where(function($q) use ($terms) {
            foreach ($terms as $term) {
                $q->orWhere('hero_title', 'LIKE', "%{$term}%")
                  ->orWhere('hero_title_en', 'LIKE', "%{$term}%")
                  ->orWhere('intro_title', 'LIKE', "%{$term}%")
                  ->orWhere('intro_title_en', 'LIKE', "%{$term}%")
                  ->orWhere('intro_text', 'LIKE', "%{$term}%")
                  ->orWhere('intro_text_en', 'LIKE', "%{$term}%")
                  ->orWhere('seo_title', 'LIKE', "%{$term}%")
                  ->orWhere('seo_description', 'LIKE', "%{$term}%")
                  ->orWhere('data', 'LIKE', "%{$term}%");
            }
        });

        $pages = $query->get();
        return $pages->map(function ($page) use ($terms, $locale) {
            $title = $locale === 'en' && $page->hero_title_en ? $page->hero_title_en : $page->hero_title;
            $intro = $locale === 'en' && $page->intro_text_en ? $page->intro_text_en : $page->intro_text;
            
            $score = $this->calculateScore($terms, [
                $title => 100,
                $page->page_slug => 90,
                $intro => 50,
                $page->seo_title => 20,
                $page->seo_description => 20,
                is_array($page->data) ? json_encode($page->data) : $page->data => 10
            ]);

            try {
                $routeMap = $this->getRouteMap();
                $routeName = $routeMap[$page->page_slug] ?? 'home';
                $url = route($routeName . '.' . $locale);
            } catch (\Exception $e) {
                $url = route('home.' . $locale);
            }

            return [
                'type' => 'card',
                'title' => $title ?: ucfirst(str_replace('-', ' ', $page->page_slug)),
                'subtitle' => $locale === 'en' ? 'Page' : 'Pagina',
                'description' => Str::limit(strip_tags($intro), 80),
                'url' => $url,
                'image' => null,
                'score' => $score
            ];
        });
    }

    protected function searchDocuments(array $terms, string $locale): Collection
    {
        $query = FinancialDocument::where('is_published', true)->whereNotNull('media_id');
        
        $query->where(function($q) use ($terms) {
            foreach ($terms as $term) {
                $q->orWhere('title', 'LIKE', "%{$term}%")
                  ->orWhere('title_en', 'LIKE', "%{$term}%")
                  ->orWhere('description', 'LIKE', "%{$term}%")
                  ->orWhere('description_en', 'LIKE', "%{$term}%")
                  ->orWhere('year', 'LIKE', "%{$term}%")
                  ->orWhere('type', 'LIKE', "%{$term}%");
            }
        });

        $docs = $query->get();
        return $docs->map(function ($doc) use ($terms, $locale) {
            $title = $locale === 'en' && $doc->title_en ? $doc->title_en : $doc->title;
            
            $score = $this->calculateScore($terms, [
                $title => 100,
                $doc->year => 50
            ]);

            return [
                'type' => 'link',
                'title' => $title . ' (' . $doc->year . ')',
                'subtitle' => 'Document',
                'description' => '',
                'url' => $doc->media ? $doc->media->url : '#',
                'image' => null,
                'score' => $score
            ];
        });
    }
}
