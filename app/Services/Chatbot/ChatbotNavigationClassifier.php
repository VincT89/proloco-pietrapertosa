<?php

namespace App\Services\Chatbot;

class ChatbotNavigationClassifier
{
    protected ChatbotSearchService $searchService;

    public function __construct(ChatbotSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function classify(string $message, string $locale): array
    {
        $messageLower = strtolower($message);
        
        // 1. Check Protected Specific Keywords
        $protected = config('chatbot_navigation.protected_keywords', []);
        foreach ($protected as $keyword) {
            if (str_contains($messageLower, $keyword)) {
                return [
                    'classification' => 'search',
                    'destination' => null,
                    'confidence' => 1.0,
                    'matched_area' => null
                ];
            }
        }

        // 2. Normalize and check length
        $terms = $this->searchService->normalizeQuery($message, $locale);
        $originalTerms = $this->searchService->normalizeQuery($message, $locale, false);
        
        // If it's a short query, it's more likely generic navigation.
        // But first let's see if it strongly matches a specific title in DB.
        if (!empty($originalTerms)) {
            $isStrongMatch = $this->searchService->findStrongTitleMatch($message, $locale, false);
            if ($isStrongMatch && count($originalTerms) > 1) {
                return [
                    'classification' => 'search',
                    'destination' => null,
                    'confidence' => 0.9,
                    'matched_area' => null
                ];
            }
        }

        $destinations = config('chatbot_navigation.destinations', []);

        // 2.5. Specific Photo Query check
        $galleryKeywords = $destinations['gallery']['generic_keywords'][$locale] ?? [];
        $hasGalleryKeyword = false;

        foreach ($galleryKeywords as $kw) {
            if (str_contains($messageLower, $kw)) {
                $hasGalleryKeyword = true;
                break;
            }
        }

        if ($hasGalleryKeyword) {
            $genericPhotoWords = $locale === 'en'
                ? ['photos', 'photo', 'gallery', 'images', 'pictures', 'see', 'view', 'show', 'find']
                : [
                    'foto',
                    'galleria',
                    'immagini',
                    'fotografie',
                    'vedere',
                    'guardare',
                    'mostrare',
                    'mostrami',
                    'avete',
                    'archivio',
                    'fotografico'
                ];

            $specificTerms = array_values(array_diff($originalTerms, $genericPhotoWords));

            if (count($specificTerms) > 0) {
                return [
                    'classification' => 'search',
                    'destination' => null,
                    'confidence' => 0.75,
                    'matched_area' => null
                ];
            }
        }

        // 3. Match Generic Navigation Destinations
        
        $matchedDestinations = [];
        foreach ($destinations as $destKey => $destData) {
            $keywords = $destData['generic_keywords'][$locale] ?? [];
            foreach ($keywords as $kw) {
                if (in_array($kw, $terms) || str_contains($messageLower, $kw)) {
                    $matchedDestinations[] = $destKey;
                    break; // match at least one keyword for this destination
                }
            }
        }

        if (count($matchedDestinations) === 1) {
            return [
                'classification' => 'navigation',
                'destination' => $matchedDestinations[0],
                'confidence' => 0.8,
                'matched_area' => null
            ];
        }

        if (count($matchedDestinations) > 1) {
            return [
                'classification' => 'mixed',
                'destination' => null,
                'confidence' => 0.5,
                'matched_area' => null
            ];
        }

        // 4. Default to search if it's long enough and didn't match generic maps
        if (count($terms) > 0) {
            return [
                'classification' => 'search',
                'destination' => null,
                'confidence' => 0.6,
                'matched_area' => null
            ];
        }

        return [
            'classification' => 'mixed',
            'destination' => null,
            'confidence' => 0.0,
            'matched_area' => null
        ];
    }
}
