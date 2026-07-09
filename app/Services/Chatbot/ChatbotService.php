<?php

namespace App\Services\Chatbot;

use App\Repositories\Chatbot\ChatbotEventRepository;
use App\Repositories\Chatbot\ChatbotDocumentRepository;
use App\Repositories\Chatbot\ChatbotNewsRepository;

class ChatbotService
{
    protected IntentDetectorService $intentDetector;
    protected ChatbotEventRepository $eventRepo;
    protected ChatbotDocumentRepository $documentRepo;
    protected ChatbotNewsRepository $newsRepo;
    protected ChatbotSearchService $searchService;
    protected ChatbotNavigationClassifier $classifier;
    protected ChatbotNavigationService $navigationService;

    public function __construct(
        IntentDetectorService $intentDetector,
        ChatbotEventRepository $eventRepo,
        ChatbotDocumentRepository $documentRepo,
        ChatbotNewsRepository $newsRepo,
        ChatbotSearchService $searchService,
        ChatbotNavigationClassifier $classifier,
        ChatbotNavigationService $navigationService
    ) {
        $this->intentDetector = $intentDetector;
        $this->eventRepo = $eventRepo;
        $this->documentRepo = $documentRepo;
        $this->newsRepo = $newsRepo;
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
            case 'today_events':
                return $this->handleTodayEvents($locale, $builder);
            case 'latest_news':
                return $this->handleLatestNews($locale, $builder);
            case 'next_event':
                return $this->handleNextEvent($locale, $builder);
            case 'weekend_events':
                return $this->handleWeekendEvents($locale, $builder);
            case 'contact_info':
                return $this->handleContactInfo($locale, $builder);
            case 'parking_info':
                return $this->handleParkingInfo($locale, $builder);
            case 'arrival_info':
                return $this->handleArrivalInfo($locale, $builder);
            case 'hours_info':
                return $this->handleHoursInfo($locale, $builder);
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

    protected function handleTodayEvents(string $locale, ChatbotResponseBuilder $builder): ChatbotResponseBuilder
    {
        $events = $this->eventRepo->getTodayEvents();

        if ($events->isEmpty()) {
            $reply = $locale === 'en'
                ? "There are no events scheduled for today."
                : "Non ci sono eventi in programma per oggi.";

            $builder->addLink($locale === 'en' ? 'View all events' : 'Vedi tutti gli eventi', route('events.' . $locale));

            return $builder->setReply($reply);
        }

        $reply = $locale === 'en'
            ? "Here are today's events:"
            : "Ecco gli eventi in programma oggi:";

        $builder->setReply($reply);

        foreach ($events as $event) {
            $title = $locale === 'en' && $event->title_en ? $event->title_en : $event->title;
            $description = $locale === 'en' && $event->description_en ? $event->description_en : $event->description;

            $builder->addCard([
                'title' => $title,
                'subtitle' => $event->start_date ? $event->start_date->format('d/m/Y H:i') : '',
                'description' => \Illuminate\Support\Str::limit(strip_tags($description), 100),
                'url' => route('events.' . $locale),
                'image' => $event->cover ? $event->cover->url : null
            ]);
        }

        $builder->addLink($locale === 'en' ? 'View all events' : 'Vedi tutti gli eventi', route('events.' . $locale));

        return $builder;
    }

    protected function handleLatestNews(string $locale, ChatbotResponseBuilder $builder): ChatbotResponseBuilder
    {
        $news = $this->newsRepo->getLatestNews();

        if ($news->isEmpty()) {
            $reply = $locale === 'en'
                ? "There are no published news items at the moment."
                : "Al momento non ci sono notizie pubblicate.";

            $builder->addLink($locale === 'en' ? 'News' : 'Notizie', route('news.' . $locale));

            return $builder->setReply($reply);
        }

        $reply = $locale === 'en'
            ? "Here are the latest news items:"
            : "Ecco le ultime notizie pubblicate:";

        $builder->setReply($reply);

        foreach ($news as $item) {
            $title = $locale === 'en' && $item->title_en ? $item->title_en : $item->title;
            $excerpt = $locale === 'en' && $item->excerpt_en ? $item->excerpt_en : $item->excerpt;
            $content = $locale === 'en' && $item->content_en ? $item->content_en : $item->content;

            $builder->addCard([
                'title' => $title,
                'subtitle' => $locale === 'en' ? 'News' : 'Notizia',
                'description' => \Illuminate\Support\Str::limit(strip_tags($excerpt ?: $content), 100),
                'url' => route('news.' . $locale),
                'image' => $item->cover ? $item->cover->url : null
            ]);
        }

        $builder->addLink($locale === 'en' ? 'View all news' : 'Vedi tutte le notizie', route('news.' . $locale));

        return $builder;
    }

    protected function handleParkingInfo(string $locale, ChatbotResponseBuilder $builder): ChatbotResponseBuilder
    {
        $reply = $locale === 'en'
            ? "I do not have official detailed parking information available on the website. To avoid giving outdated directions, please contact Pro Loco Pietrapertosa: phone +39 320 833 7801, email prolocopietrapertosa@gmail.com."
            : "Non ho informazioni ufficiali dettagliate sui parcheggi disponibili nel sito. Per evitare indicazioni non aggiornate, ti consiglio di contattare la Pro Loco Pietrapertosa: telefono 320 833 7801, email prolocopietrapertosa@gmail.com.";

        $builder->addLink(
            $locale === 'en' ? 'Pro Loco page' : 'Pagina Pro Loco',
            route('proLoco.' . $locale) . '#contatti'
        );

        return $builder->setReply($reply);
    }

    protected function handleArrivalInfo(string $locale, ChatbotResponseBuilder $builder): ChatbotResponseBuilder
    {
        $reply = $locale === 'en'
            ? "The Pro Loco Pietrapertosa headquarters are in Via della Speranza, 159, 85010 Pietrapertosa (PZ). You can find the map on the Pro Loco page. For updated information about roads, parking or special access, please contact Pro Loco directly."
            : "La sede della Pro Loco Pietrapertosa si trova in Via della Speranza, 159, 85010 Pietrapertosa (PZ). Nella pagina Pro Loco trovi anche la mappa. Per indicazioni aggiornate su viabilità, parcheggi o accessi particolari, ti consiglio di contattare direttamente la Pro Loco.";

        $builder->addLink(
            $locale === 'en' ? 'Pro Loco page' : 'Pagina Pro Loco',
            route('proLoco.' . $locale) . '#contatti'
        );

        return $builder->setReply($reply);
    }

    protected function handleHoursInfo(string $locale, ChatbotResponseBuilder $builder): ChatbotResponseBuilder
    {
        $reply = $locale === 'en'
            ? "I do not have official updated opening hours for the headquarters or info point available on the website. Please contact Pro Loco Pietrapertosa by phone at +39 320 833 7801 or by email at prolocopietrapertosa@gmail.com."
            : "Non ho orari ufficiali aggiornati della sede o dell’info point disponibili nel sito. Per avere conferma, contatta la Pro Loco Pietrapertosa al numero 320 833 7801 o via email a prolocopietrapertosa@gmail.com.";

        $builder->addLink(
            $locale === 'en' ? 'Pro Loco page' : 'Pagina Pro Loco',
            route('proLoco.' . $locale) . '#contatti'
        );

        return $builder->setReply($reply);
    }

    protected function handleContactInfo(string $locale, ChatbotResponseBuilder $builder): ChatbotResponseBuilder
    {
        $reply = $locale === 'en'
            ? "You can contact Pro Loco Pietrapertosa by phone at +39 320 833 7801, by email at prolocopietrapertosa@gmail.com, or by PEC at prolocopietrapertosa@pec.it. The headquarters are in Via della Speranza, 159, 85010 Pietrapertosa (PZ)."
            : "Puoi contattare la Pro Loco Pietrapertosa al numero 320 833 7801, via email a prolocopietrapertosa@gmail.com oppure via PEC a prolocopietrapertosa@pec.it. La sede è in Via della Speranza, 159, 85010 Pietrapertosa (PZ).";

        $builder->addLink(
            $locale === 'en' ? 'Pro Loco page' : 'Pagina Pro Loco',
            route('proLoco.' . $locale) . '#contatti'
        );

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
                    ? "I could not find enough specific information on the website to answer with certainty. You can browse the main sections or contact Pro Loco for confirmation."
                    : "Non ho trovato informazioni specifiche nel sito per rispondere con certezza. Posso aiutarti a consultare le sezioni principali oppure puoi contattare la Pro Loco per una conferma.";

                $builder->setReply($reply);

                $builder->addLink($locale === 'en' ? 'Pro Loco' : 'Pro Loco', route('proLoco.' . $locale) . '#contatti');
                $builder->addLink($locale === 'en' ? 'Events' : 'Eventi', route('events.' . $locale));
                $builder->addLink($locale === 'en' ? 'Discover & Experience' : 'Scopri & Vivi', route('discover.' . $locale));
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
