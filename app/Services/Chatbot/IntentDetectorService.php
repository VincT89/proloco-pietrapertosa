<?php

namespace App\Services\Chatbot;

class IntentDetectorService
{
    /**
     * Map of intents to keyword arrays.
     * The key is the intent name. The value is an array of keywords.
     */
    protected array $itKeywords = [
        'greeting' => [
            'ciao', 'salve', 'buongiorno', 'buonasera', 'ehi'
        ],
        'help' => [
            'aiuto', 'cosa puoi fare', 'come funzioni', 'help'
        ],
        'today_events' => [
            'eventi oggi', 'evento oggi', 'ci sono eventi oggi', 'cosa c’è oggi', 'cosa c\'è oggi', 'eventi stasera', 'stasera', 'questa sera'
        ],
        'next_event' => [
            'prossimo evento', 'prossimo appuntamento', 'evento più vicino', "cosa c'è in programma"
        ],
        'weekend_events' => [
            'weekend', 'fine settimana', 'sabato', 'domenica'
        ],
        'latest_news' => [
            'ultime notizie', 'nuove notizie', 'ultimi aggiornamenti', 'aggiornamenti recenti'
        ],
        'parking_info' => [
            'parcheggio', 'parcheggi', 'parcheggiare', 'posteggio', 'posteggiare', 'sosta', 'dove posso lasciare la macchina', 'dove lascio la macchina', 'auto', 'macchina', 'camper', 'bus', 'pullman'
        ],
        'arrival_info' => [
            'come arrivare', 'come arrivo', 'arrivare', 'raggiungere', 'indicazioni', 'strada', 'dove si trova', 'mappa', 'maps'
        ],
        'hours_info' => [
            'orari', 'orario', 'quando siete aperti', 'quando è aperto', 'apertura', 'chiusura', 'info point'
        ],
        'contact_info' => [
            'contatti', 'contatto', 'contattare', 'contatta', 'contattarvi', 'telefono', 'numero', 'chiamare', 'email', 'mail', 'pec', 'sede', 'indirizzo', 'recapiti', 'pro loco', 'proloco'
        ],
        'documents' => [
            'documenti', 'regolamento', 'statuto', 'scaricare', 'pdf', 'download', 'bilancio', 'rendiconto'
        ],
    ];

    protected array $enKeywords = [
        'greeting' => [
            'hello', 'hi', 'greetings', 'good morning', 'good afternoon'
        ],
        'help' => [
            'help', 'what can you do', 'how do you work'
        ],
        'today_events' => [
            'events today', 'tonight', 'this evening', 'what is on today'
        ],
        'next_event' => [
            'next event', 'upcoming event', 'what is on', 'programme', 'program'
        ],
        'weekend_events' => [
            'weekend', 'saturday', 'sunday'
        ],
        'latest_news' => [
            'latest news', 'new news', 'recent updates'
        ],
        'parking_info' => [
            'parking', 'car park', 'park', 'where can i park', 'camper', 'bus'
        ],
        'arrival_info' => [
            'how to get', 'how to arrive', 'directions', 'map', 'where is', 'reach'
        ],
        'hours_info' => [
            'hours', 'opening hours', 'open', 'closed', 'info point'
        ],
        'contact_info' => [
            'contact', 'contacts', 'phone', 'telephone', 'call', 'email', 'address', 'headquarters', 'pro loco'
        ],
        'documents' => [
            'documents', 'rules', 'regulation', 'pdf', 'download', 'budget'
        ],
    ];

    /**
     * Detect the intent from a normalized text message.
     */
    public function detect(string $message, string $locale): string
    {
        $message = strtolower(trim($message));
        $keywords = $locale === 'en' ? $this->enKeywords : $this->itKeywords;

        // Try exact/partial matching based on simple strings
        foreach ($keywords as $intent => $phrases) {
            foreach ($phrases as $phrase) {
                if (str_contains($message, $phrase)) {
                    return $intent;
                }
            }
        }

        // If no predefined intent matches, return the fallback intent
        return 'fallback_search';
    }
}
