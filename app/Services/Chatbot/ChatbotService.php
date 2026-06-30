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
    protected ChatbotNavigationClassifier $classifier;
    protected ChatbotNavigationService $navigationService;

    public function __construct(
        IntentDetectorService $intentDetector,
        ChatbotEventRepository $eventRepo,
        ChatbotDocumentRepository $documentRepo,
        ChatbotSearchService $searchService,
        ChatbotNavigationClassifier $classifier,
        ChatbotNavigationService $navigationService
    ) {
        $this->intentDetector = $intentDetector;
        $this->eventRepo = $eventRepo;
        $this->documentRepo = $documentRepo;
        $this->searchService = $searchService;
        $this->classifier = $classifier;
        $this->navigationService = $navigationService;
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
        $builder->setContext(array_merge($context, [
            'topic' => $intent,
            'last_intent' => $intent
        ]));

        $builder = $this->routeIntent($intent, $message, $locale, $builder);

        // Se è un search di fallback, gestisce lui il log.
        // Se è 'documents' ma con query lunga, viene deviato al search.
        $messageLength = strlen(trim(str_replace(['documenti', 'documents', 'scaricare', 'download'], '', strtolower($message))));
        $deferredSearch = ($intent === 'documents' && $messageLength > 5);

        if ($intent !== 'fallback_search' && !$deferredSearch && config('chatbot.logging_enabled', true)) {
            try {
                $queryToLog = config('chatbot.log_raw_query', false) 
                    ? $message 
                    : hash('sha256', trim(strtolower($message)));
                
                \App\Models\ChatbotLog::create([
                    'locale' => $locale,
                    'query' => $queryToLog,
                    'mode' => 'intent',
                    'matched_destination' => $intent,
                    'result_count' => 1,
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('ChatbotLog creation failed: ' . $e->getMessage());
            }
        }

        return $builder->build();
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
                return $this->handleNavigationOrSearch($message, $locale, $builder);
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
            'subtitle' => $event->start_date ? $event->start_date->format('d/m/Y H:i') : ($locale === 'en' ? 'Date TBA' : 'Data da definire'),
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
                'subtitle' => $event->start_date ? $event->start_date->format('d/m/Y') : '',
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
            return $this->handleNavigationOrSearch($message, $locale, $builder);
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

    protected function handleNavigationOrSearch(string $message, string $locale, ChatbotResponseBuilder $builder): ChatbotResponseBuilder
    {
        $classification = $this->classifier->classify($message, $locale);
        $results = collect();

        if ($classification['classification'] === 'navigation') {
            $builder = $this->navigationService->buildResponse($classification, $locale, $builder);
        } elseif ($classification['classification'] === 'mixed') {
            $builder = $this->navigationService->buildAmbiguousResponse($locale, $builder);
        } else {
            // search
            $results = $this->searchService->search($message, $locale);
            
            if ($results->isEmpty()) {
                $reply = $locale === 'en' 
                    ? "I could not find enough specific information on the website to answer this question." 
                    : "Non ho trovato informazioni specifiche nel sito per rispondere a questa domanda.";
                $builder->setReply($reply);
            } else {
                $reply = $locale === 'en' ? "Here is what I found:" : "Ecco cosa ho trovato nel sito:";
                $builder->setReply($reply);
                
                foreach ($results as $result) {
                    if ($result['type'] === 'card') {
                        $builder->addCard([
                            'title' => $result['title'],
                            'subtitle' => $result['subtitle'] ?? null,
                            'description' => $result['description'] ?? null,
                            'url' => $result['url'] ?? '#',
                            'image' => $result['image'] ?? null
                        ]);
                    } elseif ($result['type'] === 'link') {
                        $builder->addLink($result['title'], $result['url']);
                    }
                }
            }
        }

        // Log the query if enabled
        if (config('chatbot.logging_enabled', true)) {
            try {
                $queryToLog = config('chatbot.log_raw_query', false) 
                    ? $message 
                    : hash('sha256', trim(strtolower($message)));
                
                \App\Models\ChatbotLog::create([
                    'locale' => $locale,
                    'query' => $queryToLog,
                    'mode' => $classification['classification'],
                    'matched_destination' => $classification['destination'],
                    'result_count' => $results->count(),
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('ChatbotLog creation failed: ' . $e->getMessage());
            }
        }

        return $builder;
    }
}
