<?php

namespace App\Services\Chatbot;

class IntentDetectorService
{
    /**
     * Map of intents to keyword arrays.
     * The key is the intent name. The value is an array of keywords.
     */
    protected array $itKeywords = [
        'greeting' => ['ciao', 'salve', 'buongiorno', 'buonasera', 'ehi'],
        'help' => ['aiuto', 'cosa puoi fare', 'come funzioni', 'help'],
        'next_event' => ['prossimo evento', 'prossimo appuntamento', 'evento più vicino', "cosa c'è in programma"],
        'weekend_events' => ['weekend', 'fine settimana', 'sabato', 'domenica'],
        'contact_info' => ['contatti', 'telefono', 'email', 'pec', 'sede', 'indirizzo', 'contattare'],
        'documents' => ['documenti', 'regolamento', 'statuto', 'scaricare', 'pdf', 'download'],
    ];

    protected array $enKeywords = [
        'greeting' => ['hello', 'hi', 'greetings', 'good morning', 'good afternoon'],
        'help' => ['help', 'what can you do', 'how do you work'],
        'next_event' => ['next event', 'upcoming event', 'what is on', 'programme'],
        'weekend_events' => ['weekend', 'saturday', 'sunday'],
        'contact_info' => ['contact', 'phone', 'email', 'address'],
        'documents' => ['documents', 'rules', 'regulation', 'pdf', 'download'],
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
