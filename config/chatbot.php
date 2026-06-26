<?php

return [
    'logging_enabled' => env('CHATBOT_LOGGING_ENABLED', true),
    
    // Whether to store the full raw query or just the normalized excerpt
    'log_raw_query' => env('CHATBOT_LOG_RAW_QUERY', false),

    'stopwords' => [
        'it' => ['dove', 'cosa', 'come', 'posso', 'puoi', 'mi', 'del', 'della', 'dei', 'le', 'gli', 'il', 'lo', 'la', 'un', 'una', 'l', 'vorrei', 'sapere', 'parlami', 'dimmi', 'informazioni', 'su', 'sui', 'sulle', 'per', 'di', 'da', 'in', 'con', 'chi', 'quando', 'perché', 'cerco', 'cercare', 'trovo', 'trovare', 'ci', 'sono', 'hai'],
        'en' => ['what', 'where', 'how', 'can', 'i', 'you', 'the', 'a', 'an', 'of', 'about', 'me', 'tell', 'want', 'know', 'information', 'on', 'in', 'with', 'who', 'when', 'why', 'search', 'find', 'are', 'there', 'have']
    ],

    'synonyms' => [
        'mangiare' => ['mangiare', 'ristorante', 'sapori', 'mangio', 'pranzo', 'cena', 'trattoria', 'eat', 'food', 'restaurant', 'lunch', 'dinner'],
        'mangio' => ['mangiare', 'ristorante', 'sapori'],
        'pranzo' => ['mangiare', 'ristorante', 'sapori'],
        'cena' => ['mangiare', 'ristorante', 'sapori'],
        'trattoria' => ['mangiare', 'ristorante', 'sapori'],
        'ristoranti' => ['mangiare', 'ristorante', 'sapori'],
        'dormire' => ['dormire', 'alloggi', 'ospitalità', 'pernottare', 'hotel', 'b&b', 'camere', 'sleep', 'stay', 'accommodation', 'room'],
        'pernottare' => ['dormire', 'alloggi', 'ospitalità'],
        'alloggio' => ['dormire', 'alloggi', 'ospitalità'],
        'vedere' => ['visitare', 'scopri', 'luoghi', 'see', 'visit', 'attractions'],
        'visitare' => ['visitare', 'scopri', 'luoghi'],
        'volo' => ['volo', 'angelo'],
        'arabata' => ['arabata', 'tracce', 'arabi'],
        'castello' => ['castello', 'saraceno']
    ],
];
