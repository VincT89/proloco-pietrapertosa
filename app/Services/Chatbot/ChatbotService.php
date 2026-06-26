<?php

namespace App\Services\Chatbot;

use App\Repositories\Chatbot\ChatbotEventRepository;
use App\Repositories\Chatbot\ChatbotDocumentRepository;

class ChatbotService
{
    protected IntentDetectorService $intentDetector;
    protected ChatbotEventRepository $eventRepo;
    protected ChatbotDocumentRepository $documentRepo;
    protected ChatbotSearchService $searchService;

    public function __construct(
        IntentDetectorService $intentDetector,
        ChatbotEventRepository $eventRepo,
        ChatbotDocumentRepository $documentRepo,
        ChatbotSearchService $searchService
    ) {
        $this->intentDetector = $intentDetector;
        $this->eventRepo = $eventRepo;
        $this->documentRepo = $documentRepo;
        $this->searchService = $searchService;
    }

    public function getInitialSuggestions(string $locale): array
    {
        if ($locale === 'en') {
            return [
                'What is the next event?',
                'What can I visit?',
                'Where can I eat?',
                'How can I contact the Pro Loco?'
            ];
        }

        return [
            'Qual è il prossimo evento?',
            'Cosa posso visitare?',
            'Dove mangiare?',
            'Come contattare la Pro Loco?'
        ];
    }

    public function handleMessage(string $message, string $locale, array $context): array
    {
        $intent = $this->intentDetector->detect($message, $locale);
        
        $builder = new ChatbotResponseBuilder();
        $builder->setContext([
            'topic' => $intent,
            'entity_type' => null,
            'entity_id' => null
        ]);

        return $this->routeIntent($intent, $message, $locale, $builder)->build();
    }

    protected function routeIntent(string $intent, string $message, string $locale, ChatbotResponseBuilder $builder): ChatbotResponseBuilder
    {
        switch ($intent) {
            case 'greeting':
                return $this->handleGreeting($locale, $builder);
            case 'help':
                return $this->handleHelp($locale, $builder);
            case 'next_event':
                return $this->handleNextEvent($locale, $builder);
            case 'weekend_events':
                return $this->handleWeekendEvents($locale, $builder);
            case 'contact_info':
                return $this->handleContactInfo($locale, $builder);
            case 'documents':
                return $this->handleDocuments($message, $locale, $builder);
            case 'fallback_search':
            default:
                return $this->handleSearch($message, $locale, $builder);
        }
    }

    protected function handleGreeting(string $locale, ChatbotResponseBuilder $builder): ChatbotResponseBuilder
    {
        $reply = $locale === 'en' 
            ? "Hello! I can answer your questions about Pietrapertosa. I can search our events, news, places, typical foods, and documents. What would you like to know?"
            : "Ciao! Posso rispondere alle tue domande su Pietrapertosa. Cercherò tra i nostri eventi, luoghi, sapori tipici e documenti. Cosa vorresti sapere?";
        
        return $builder->setReply($reply);
    }

    protected function handleHelp(string $locale, ChatbotResponseBuilder $builder): ChatbotResponseBuilder
    {
        $reply = $locale === 'en' 
            ? "I can assist you with: finding the next events, suggesting places to visit, answering specific questions about our village, and providing contact info."
            : "Posso aiutarti con: trovare i prossimi eventi, suggerirti cosa visitare, rispondere a domande specifiche sul nostro borgo e fornirti i contatti.";
        
        return $builder->setReply($reply);
    }

    protected function handleNextEvent(string $locale, ChatbotResponseBuilder $builder): ChatbotResponseBuilder
    {
        $event = $this->eventRepo->getNextEvent();

        if (!$event) {
            $reply = $locale === 'en' ? "There are no upcoming events scheduled at the moment." : "Al momento non ci sono prossimi eventi in programma.";
            return $builder->setReply($reply);
        }

        $reply = $locale === 'en' ? "Here is the next event:" : "Ecco il prossimo evento in programma:";
        $builder->setReply($reply);
        
        $title = $locale === 'en' && $event->title_en ? $event->title_en : $event->title;
        $description = $locale === 'en' && $event->description_en ? $event->description_en : $event->description;

        $builder->addCard([
            'title' => $title,
            'subtitle' => $event->start_date->format('d/m/Y H:i'),
            'description' => \Illuminate\Support\Str::limit(strip_tags($description), 100),
            'url' => route('events.' . $locale),
            'image' => $event->cover ? $event->cover->url : null
        ]);

        $builder->addLink($locale === 'en' ? 'View all events' : 'Vedi tutti gli eventi', route('events.' . $locale));

        return $builder;
    }

    protected function handleWeekendEvents(string $locale, ChatbotResponseBuilder $builder): ChatbotResponseBuilder
    {
        $events = $this->eventRepo->getWeekendEvents();

        if ($events->isEmpty()) {
            $reply = $locale === 'en' ? "There are no events scheduled for this weekend." : "Non ci sono eventi in programma per questo fine settimana.";
            return $builder->setReply($reply);
        }

        $reply = $locale === 'en' ? "Here are the events for this weekend:" : "Ecco gli eventi in programma questo fine settimana:";
        $builder->setReply($reply);

        foreach ($events as $event) {
            $title = $locale === 'en' && $event->title_en ? $event->title_en : $event->title;
            $builder->addCard([
                'title' => $title,
                'subtitle' => $event->start_date->format('d/m/Y'),
                'description' => '',
                'url' => route('events.' . $locale),
                'image' => $event->cover ? $event->cover->url : null
            ]);
        }

        return $builder;
    }

    protected function handleContactInfo(string $locale, ChatbotResponseBuilder $builder): ChatbotResponseBuilder
    {
        $reply = $locale === 'en' 
            ? "You can contact the Pro Loco via email at prolocopietrapertosa@gmail.com. Visit our contact page for the form and address."
            : "Puoi contattare la Pro Loco tramite email scrivendo a prolocopietrapertosa@gmail.com. Visita la pagina contatti per usare il form o vedere l'indirizzo.";
            
        $builder->addLink($locale === 'en' ? 'Contact Page' : 'Pagina Contatti', route('proLoco.' . $locale));
        return $builder->setReply($reply);
    }

    protected function handleDocuments(string $message, string $locale, ChatbotResponseBuilder $builder): ChatbotResponseBuilder
    {
        // Se la richiesta è generica ("documenti"), diamo gli ultimi bilanci.
        // Se è specifica, lasciamo che SearchService peschi.
        $messageLength = strlen(trim(str_replace(['documenti', 'documents', 'scaricare', 'download'], '', strtolower($message))));
        
        if ($messageLength > 5) {
            // È una richiesta specifica (es. "cerco il bilancio 2023"). Usiamo il search service
            return $this->handleSearch($message, $locale, $builder);
        }

        $docs = $this->documentRepo->getLatestFinancialDocuments();
        $reply = $locale === 'en' ? "You can consult these documents:" : "Puoi consultare questi documenti pubblici:";
        
        $builder->setReply($reply);

        if ($docs->isEmpty()) {
            $reply = $locale === 'en' ? "There are no public documents available right now." : "Non ci sono documenti pubblici disponibili al momento.";
            return $builder->setReply($reply);
        }

        foreach ($docs as $doc) {
            $title = $locale === 'en' && $doc->title_en ? $doc->title_en : $doc->title;
            $builder->addLink($title . ' (' . $doc->year . ')', $doc->media ? $doc->media->url : '#');
        }

        return $builder;
    }

    protected function handleSearch(string $message, string $locale, ChatbotResponseBuilder $builder): ChatbotResponseBuilder
    {
        $terms = $this->searchService->normalizeQuery($message, $locale);
        
        $msgLower = strtolower($message);
        $borgoRaccontaRegex = '/(mangiare|mangio|ristorante|ristoranti|trattoria|pranzo|cena|cucina|dormire|pernottare|alloggio|alloggi|hotel|b&b|camere|ospitalità|visitare|vedere|attrazioni|luoghi|itinerari|eat|restaurant|food|lunch|dinner|sleep|stay|accommodation|room|visit|see|places)/';
        $needsBorgoRacconta = preg_match($borgoRaccontaRegex, $msgLower);

        if (empty($terms)) {
            $reply = $locale === 'en' 
                ? "You can ask me for information about events, places to visit, tastes, excellences, documents and contacts." 
                : "Puoi chiedermi informazioni su eventi, luoghi da visitare, sapori, eccellenze, documenti e contatti.";
            
            if ($needsBorgoRacconta) {
                $reply .= "\n\n" . ($locale === 'en' 
                    ? "For more information on places, hospitality and services you can also consult the Borgo Racconta portal." 
                    : "Per maggiori informazioni su luoghi, ospitalità e servizi puoi consultare anche il portale Borgo Racconta.");
                $builder->addLink('Borgo Racconta', 'https://www.borgoracconta.it/citta/pietrapertosa/');
            }
            return $builder->setReply($reply);
        }

        $results = $this->searchService->search($message, $locale);

        if ($results->isEmpty()) {
            // Fallback specifico per come arrivare / info point
            $isHowToArrive = str_contains($msgLower, 'come arrivare') || str_contains($msgLower, 'raggiungere') || str_contains($msgLower, 'how to get') || str_contains($msgLower, 'directions');
            $isInfoPoint = str_contains($msgLower, 'info point') || str_contains($msgLower, 'orari');

            if ($isHowToArrive) {
                $reply = $locale === 'en' 
                    ? "I could not find updated information on how to reach Pietrapertosa. You can contact the Pro Loco from the contact page."
                    : "Non ho trovato informazioni aggiornate su come raggiungere Pietrapertosa nel sito. Puoi contattare la Pro Loco dalla pagina contatti.";
                $builder->addLink($locale === 'en' ? 'Contact Us' : 'Contattaci', route('proLoco.' . $locale));
            } elseif ($isInfoPoint) {
                $reply = $locale === 'en' 
                    ? "I could not find updated Info Point hours on the website. You can contact the Pro Loco from the contact page."
                    : "Non ho trovato orari aggiornati dell’Info Point nel sito. Puoi contattare la Pro Loco dalla pagina contatti.";
                $builder->addLink($locale === 'en' ? 'Contact Us' : 'Contattaci', route('proLoco.' . $locale));
            } else {
                $reply = $locale === 'en' 
                    ? "I could not find enough information on the website to answer this question." 
                    : "Non ho trovato informazioni sufficienti nel sito per rispondere a questa domanda.";
            }

            if ($needsBorgoRacconta) {
                $reply .= "\n\n" . ($locale === 'en' 
                    ? "For more information on places, hospitality and services you can also consult the Borgo Racconta portal." 
                    : "Per maggiori informazioni su luoghi, ospitalità e servizi puoi consultare anche il portale Borgo Racconta.");
                $builder->addLink('Borgo Racconta', 'https://www.borgoracconta.it/citta/pietrapertosa/');
            }

            return $builder->setReply($reply);
        }

        $reply = $locale === 'en' 
            ? "I found this information on the website:" 
            : "Ho trovato queste informazioni sul sito:";
        
        if ($needsBorgoRacconta) {
            $reply .= "\n\n" . ($locale === 'en' 
                ? "For more information on places, hospitality and services you can also consult the Borgo Racconta portal." 
                : "Per maggiori informazioni su luoghi, ospitalità e servizi puoi consultare anche il portale Borgo Racconta.");
            $builder->addLink('Borgo Racconta', 'https://www.borgoracconta.it/citta/pietrapertosa/');
        }

        $builder->setReply($reply);

        foreach ($results as $result) {
            if ($result['type'] === 'card') {
                $builder->addCard([
                    'title' => $result['title'],
                    'subtitle' => $result['subtitle'],
                    'description' => $result['description'],
                    'url' => $result['url'],
                    'image' => $result['image']
                ]);
            } elseif ($result['type'] === 'link') {
                $builder->addLink($result['title'], $result['url']);
            }
        }

        return $builder;
    }
}
