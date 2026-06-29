<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;

class SeoService
{
    /**
     * Generate Canonical and Hreflang tags dynamically based on the current route.
     *
     * @return string
     */
    public static function generateTags(): string
    {
        $tags = [];
        
        // 1. Canonical URL (without query parameters)
        $currentUrl = url()->current();
        $tags[] = '<link rel="canonical" href="' . $currentUrl . '">';

        // 2. Hreflang tags
        $routeName = Route::currentRouteName();

        if ($routeName) {
            // Extract the base route name (e.g., 'home.it' -> 'home')
            $baseName = preg_replace('/\.(it|en)$/', '', $routeName);
            
            $hasIt = Route::has($baseName . '.it');
            $hasEn = Route::has($baseName . '.en');
            
            if ($hasIt && $hasEn) {
                $params = request()->route()->parameters();
                
                $itUrl = route($baseName . '.it', $params);
                $enUrl = route($baseName . '.en', $params);
                // The root / redirects to /it, so x-default should point to IT
                $defaultUrl = route($baseName . '.it', $params);

                $tags[] = '<link rel="alternate" hreflang="it" href="' . $itUrl . '">';
                $tags[] = '<link rel="alternate" hreflang="en" href="' . $enUrl . '">';
                $tags[] = '<link rel="alternate" hreflang="x-default" href="' . $defaultUrl . '">';
            }
        }

        return implode("\n    ", $tags);
    }
}
