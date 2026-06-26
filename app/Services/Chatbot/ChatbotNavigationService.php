<?php

namespace App\Services\Chatbot;

class ChatbotNavigationService
{
    public function buildResponse(array $classification, string $locale, ChatbotResponseBuilder $builder): ChatbotResponseBuilder
    {
        $destKey = $classification['destination'];
        
        if ($classification['classification'] === 'ambiguous') {
            return $this->buildAmbiguousResponse($locale, $builder);
        }

        $destinations = config('chatbot_navigation.destinations', []);
        if (!isset($destinations[$destKey])) {
            return $this->buildAmbiguousResponse($locale, $builder);
        }

        $destData = $destinations[$destKey];
        $label = $destData['labels'][$locale] ?? $destData['labels']['it'];
        
        if (isset($destData['external_url'])) {
            $url = $destData['external_url'];
        } else {
            $routeName = $destData['route'] . '.' . $locale;
            $url = route($routeName);
        }

        $reply = $locale === 'en'
            ? "You can visit the {$label} section to find the information you're looking for."
            : "Puoi visitare la sezione {$label} per trovare le informazioni che cerchi.";

        $builder->setReply($reply);
        $builder->addLink($label, $url);

        // Add Borgo Racconta if destination is tastes or pro_loco (services/hospitality)
        if (in_array($destKey, ['tastes', 'pro_loco'])) {
            $this->appendBorgoRacconta($locale, $builder);
        }

        return $builder;
    }

    public function buildAmbiguousResponse(string $locale, ChatbotResponseBuilder $builder): ChatbotResponseBuilder
    {
        $reply = $locale === 'en'
            ? "I'm not exactly sure what you're looking for. Here are some sections you might find interesting:"
            : "Non sono sicuro di aver capito esattamente cosa cerchi. Ecco alcune sezioni che potrebbero interessarti:";

        $builder->setReply($reply);
        
        $destinations = config('chatbot_navigation.destinations', []);
        
        // Add a few main sections
        $mainKeys = ['discover', 'tastes', 'events'];
        foreach ($mainKeys as $key) {
            if (isset($destinations[$key])) {
                $label = $destinations[$key]['labels'][$locale] ?? $destinations[$key]['labels']['it'];
                $url = route($destinations[$key]['route'] . '.' . $locale);
                $builder->addLink($label, $url);
            }
        }

        $builder->addQuickReply($locale === 'en' ? 'Events' : 'Eventi', 'eventi');
        $builder->addQuickReply($locale === 'en' ? 'Tastes' : 'Sapori', 'sapori');

        return $builder;
    }

    public function appendBorgoRacconta(string $locale, ChatbotResponseBuilder $builder): void
    {
        $currentReply = $builder->getReply();
        $suffix = $locale === 'en'
            ? "For more information on places, hospitality and services you can also consult the Borgo Racconta portal."
            : "Per maggiori informazioni su luoghi, ospitalità e servizi puoi consultare anche il portale Borgo Racconta.";

        $builder->setReply($currentReply ? $currentReply . "\n\n" . $suffix : $suffix);
        $builder->addLink('Borgo Racconta', 'https://www.borgoracconta.it/citta/pietrapertosa/');
    }
}
