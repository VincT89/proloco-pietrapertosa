<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Navigation Destinations
    |--------------------------------------------------------------------------
    |
    | Define the destinations and the keywords that trigger a generic navigation.
    |
    */
    'destinations' => [
        'discover' => [
            'route' => 'discover',
            'labels' => [
                'it' => 'Scopri & Vivi',
                'en' => 'Discover & Experience'
            ],
            'generic_keywords' => [
                'it' => ['visitare', 'vedere', 'fare', 'luoghi', 'attrazioni', 'itinerari', 'scoprire', 'natura', 'panorama', 'belvedere', 'escursioni', 'trekking', 'percorsi', 'sentieri'],
                'en' => ['visit', 'see', 'do', 'places', 'attractions', 'itineraries', 'discover', 'nature', 'panorama', 'viewpoint', 'excursions', 'trekking', 'routes', 'trails']
            ]
        ],
        'tastes' => [
            'route' => 'tastes',
            'labels' => [
                'it' => 'Sapori',
                'en' => 'Tastes'
            ],
            'generic_keywords' => [
                'it' => ['sapori', 'mangiare', 'ristoranti', 'trattoria', 'pranzo', 'cena', 'cibo', 'prodotti tipici'],
                'en' => ['tastes', 'eat', 'restaurants', 'lunch', 'dinner', 'food', 'typical products']
            ]
        ],
        'events' => [
            'route' => 'events',
            'labels' => [
                'it' => 'Eventi',
                'en' => 'Events'
            ],
            'generic_keywords' => [
                'it' => ['eventi', 'manifestazioni', 'feste'],
                'en' => ['events', 'festivals', 'parties']
            ]
        ],
        'news' => [
            'route' => 'news',
            'labels' => [
                'it' => 'Notizie',
                'en' => 'News'
            ],
            'generic_keywords' => [
                'it' => ['notizie', 'novità', 'aggiornamenti'],
                'en' => ['news', 'updates']
            ]
        ],
        'gallery' => [
            'route' => 'gallery',
            'labels' => [
                'it' => 'Galleria Fotografica',
                'en' => 'Photo Gallery'
            ],
            'generic_keywords' => [
                'it' => ['foto', 'galleria', 'immagini', 'fotografie'],
                'en' => ['photos', 'gallery', 'images', 'pictures']
            ]
        ],
        'community' => [
            'route' => 'community',
            'labels' => [
                'it' => 'Comunità',
                'en' => 'Community'
            ],
            'generic_keywords' => [
                'it' => ['comunità', 'storie', 'tradizioni', 'borgo'],
                'en' => ['community', 'stories', 'traditions', 'village']
            ]
        ],
        'excellences' => [
            'route' => 'excellences',
            'labels' => [
                'it' => 'Eccellenze',
                'en' => 'Excellences'
            ],
            'generic_keywords' => [
                'it' => ['eccellenze'],
                'en' => ['excellences']
            ]
        ],
        'pro_loco' => [
            'route' => 'proLoco',
            'labels' => [
                'it' => 'Pro Loco',
                'en' => 'Pro Loco'
            ],
            'generic_keywords' => [
                'it' => ['pro loco', 'contatti', 'orari', 'sede', 'info point', 'parcheggi', 'come arrivare', 'servizi'],
                'en' => ['pro loco', 'contacts', 'hours', 'info point', 'parking', 'how to get', 'services']
            ]
        ],
        'sleep' => [
            'external_url' => 'https://www.borgoracconta.it/citta/pietrapertosa/',
            'labels' => [
                'it' => 'Ospitalità (Borgo Racconta)',
                'en' => 'Hospitality (Borgo Racconta)'
            ],
            'generic_keywords' => [
                'it' => ['ospitalità', 'dormire', 'alloggi', 'hotel', 'b&b', 'pernottare', 'strutture ricettive'],
                'en' => ['hospitality', 'sleep', 'stay', 'accommodation', 'hotel', 'b&b', 'room']
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Protected Specific Keywords
    |--------------------------------------------------------------------------
    |
    | If the query contains any of these, it's considered a specific search
    | even if it has generic terms.
    |
    */
    'protected_keywords' => [
        'arabata',
        'sulle tracce degli arabi',
        'castello',
        'castello saraceno',
        'volo dell\'angelo',
        'via ferrata',
        'sapori d\'autunno',
        'rafanata',
        'pasta fresca'
    ],
];
