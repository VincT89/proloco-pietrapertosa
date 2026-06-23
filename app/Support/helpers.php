<?php

if (! function_exists('localized_route')) {
    /**
     * Mappa l'URL corrente verso la lingua desiderata, preservando le query string.
     */
    function localized_route(string $currentPath, string $targetLocale): string
    {
        $segments = explode('/', ltrim($currentPath, '/'));
        
        // Remove current locale
        if (isset($segments[0]) && in_array($segments[0], ['it', 'en'])) {
            array_shift($segments);
        }

        $slug = $segments[0] ?? '';

        // Slug mapping
        $mapItToEn = [
            'eventi' => 'events',
            'notizie' => 'news',
            'galleria' => 'gallery',
            'contatti' => 'contact',
            'comunita' => 'community',
            'territorio' => 'territory',
            'sapori' => 'tastes',
            'scopri' => 'discover',
        ];

        $mapEnToIt = array_flip($mapItToEn);

        if ($targetLocale === 'en') {
            if (array_key_exists($slug, $mapItToEn)) {
                $segments[0] = $mapItToEn[$slug];
            }
        } else {
            if (array_key_exists($slug, $mapEnToIt)) {
                $segments[0] = $mapEnToIt[$slug];
            }
        }

        $newPath = implode('/', $segments);
        $baseUrl = url("/{$targetLocale}" . ($newPath ? '/' . $newPath : ''));

        $queryString = request()->getQueryString();
        if ($queryString) {
            $baseUrl .= '?' . $queryString;
        }

        return $baseUrl;
    }
}
