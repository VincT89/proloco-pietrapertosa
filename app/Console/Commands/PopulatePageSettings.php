<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Media;
use App\Models\PageSetting;

#[Signature('app:populate-page-settings')]
#[Description('Command description')]
class PopulatePageSettings extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Popolamento PageSettings in corso...');

        $pagesData = [
            'home' => [
                'hero_title' => '<span class="gold">Pietrapertosa</span> vive', 'hero_title_en' => '<span class="gold">Pietrapertosa</span> lives',
                'hero_subtitle' => 'tutto l\'<span class="gold">anno</span>', 'hero_subtitle_en' => 'all year <span class="gold">round</span>',
                'intro_text' => '<p>' . __('home.hero_desc', [], 'it') . '</p>',
                'intro_text_en' => '<p>' . __('home.hero_desc', [], 'en') . '</p>',
                'hero_image' => 'immaginePaese.jpg',
                'data' => [
                    'events_title' => 'Prossimi Eventi', 'events_title_en' => 'Upcoming Events',
                    'news_title' => 'Ultime Notizie e Avvisi', 'news_title_en' => 'Latest News',
                    'discover_subtitle' => 'ESPLORA IL TERRITORIO', 'discover_subtitle_en' => 'EXPLORE THE TERRITORY',
                    'discover_title' => 'Scopri Pietrapertosa', 'discover_title_en' => 'Discover Pietrapertosa',
                    'discover_text' => 'Dall\'Arabata al Castello Normanno-Svevo, fino all\'emozionante Volo dell\'Angelo. Scopri i luoghi che rendono unico il nostro borgo.',
                    'discover_text_en' => 'From the Arabata to the Norman-Swabian Castle, to the exciting Flight of the Angel. Discover the places that make our village unique.',
                    'discover_items' => [
                        ['nome' => 'L\'Arabata', 'nome_en' => 'The Arabata', 'img' => url('images/arabata01.jpg')],
                        ['nome' => 'Castello Normanno-Svevo', 'nome_en' => 'Norman-Swabian Castle', 'img' => url('images/castello.jpg')],
                        ['nome' => 'Sentiero delle Sette Pietre', 'nome_en' => 'Path of the Seven Stones', 'img' => url('images/percorsi.jpg')],
                        ['nome' => 'Volo dell\'Angelo', 'nome_en' => 'Flight of the Angel', 'img' => url('images/voloAngelo.jpg')]
                    ]
                ]
            ],
            'il-borgo' => [
                'hero_title' => 'Il Borgo di Pietrapertosa', 'hero_title_en' => 'The Village of Pietrapertosa',
                'hero_subtitle' => 'Un paese sospeso tra cielo e roccia', 'hero_subtitle_en' => 'A village suspended between sky and rock',
                'intro_title' => 'Un tuffo nel passato', 'intro_title_en' => 'A dive into the past',
                'intro_text' => '<p>Incastonato nelle Dolomiti Lucane, a 1.088 metri d\'altezza, Pietrapertosa è il comune più alto della Basilicata. Le sue origini si perdono nella notte dei tempi, tra antiche civiltà rupestri e dominazioni bizantine, arabe e normanne. Dalle fortificazioni saracene fino alle porte delle case incastrate nella pietra viva, ogni angolo racconta di un popolo fiero e di tradizioni secolari custodite dalle montagne.</p>',
                'intro_text_en' => '<p>Nestled in the Lucanian Dolomites, at 1,088 meters above sea level, Pietrapertosa is the highest municipality in Basilicata. Its origins are lost in the mists of time, amidst ancient rock civilizations and Byzantine, Arab, and Norman dominations. From the Saracen fortifications to the doors of the houses wedged into the living rock, every corner tells of a proud people and secular traditions guarded by the mountains.</p>',
                'hero_image' => 'immaginePaese.jpg'
            ],
            'pro-loco' => [
                'hero_title' => 'La Pro Loco', 'hero_title_en' => 'The Pro Loco',
                'hero_subtitle' => 'Uniti per Pietrapertosa', 'hero_subtitle_en' => 'United for Pietrapertosa',
                'intro_title' => 'Chi Siamo', 'intro_title_en' => 'Who We Are',
                'intro_text' => '<p>Siamo un gruppo di cittadini innamorati del proprio paese, uniti dall\'obiettivo di promuovere e valorizzare il patrimonio culturale, storico e umano di Pietrapertosa.</p>',
                'intro_text_en' => '<p>We are a group of citizens in love with our village, united by the goal of promoting and enhancing the cultural, historical and human heritage of Pietrapertosa.</p>',
                'hero_image' => 'ArabataHome.jpg'
            ],
            'comunita' => [
                'hero_title' => 'La Comunità in Primo Piano', 'hero_title_en' => 'The Community in the Foreground',
                'hero_subtitle' => 'Le persone, il cuore del borgo', 'hero_subtitle_en' => 'The people, the heart of the village',
                'intro_title' => 'Scopri la nostra realtà', 'intro_title_en' => 'Discover our reality',
                'intro_text' => '<p>Le associazioni che mantengono vivo il paese.</p>',
                'intro_text_en' => '<p>The associations that keep the village alive.</p>',
                'hero_image' => 'immaginePaese.jpg'
            ],
            'territorio' => [
                'hero_title' => 'Esplora il Territorio', 'hero_title_en' => 'Explore the territory',
                'hero_subtitle' => 'Natura e avventura', 'hero_subtitle_en' => 'Nature and adventure',
                'intro_title' => 'Un territorio unico', 'intro_title_en' => 'A unique territory',
                'intro_text' => '<p>Scopri le eccellenze di Pietrapertosa.</p>',
                'intro_text_en' => '<p>Discover the excellence of Pietrapertosa.</p>',
                'hero_image' => 'percorsi.jpg'
            ],
            'sapori' => [
                'hero_title' => 'I Sapori della Tradizione', 'hero_title_en' => 'Traditional Tastes',
                'hero_subtitle' => 'Gusti autentici', 'hero_subtitle_en' => 'Authentic tastes',
                'intro_title' => 'Un viaggio culinario', 'intro_title_en' => 'A culinary journey',
                'intro_text' => '<p>Scopri i piatti tipici delle nostre montagne.</p>',
                'intro_text_en' => '<p>Discover the typical dishes of our mountains.</p>',
                'hero_image' => 'sapori_hero.png'
            ],
            'scopri' => [
                'hero_title' => 'Scopri e Vivi', 'hero_title_en' => 'Discover and Live',
                'hero_subtitle' => 'Vivi Pietrapertosa', 'hero_subtitle_en' => 'Experience Pietrapertosa',
                'intro_title' => 'I luoghi imperdibili', 'intro_title_en' => 'Must-see places',
                'intro_text' => '<p>Esplora le meraviglie storiche e naturalistiche del nostro paese. E quando è l\'ora di riposare o mangiare, affidati alle nostre strutture accoglienti.</p>',
                'intro_text_en' => '<p>Explore the historical and naturalistic wonders of our village. And when it\'s time to rest or eat, trust our welcoming facilities.</p>',
                'hero_image' => 'voloAngelo.jpg'
            ],
            'storie' => [
                'hero_title' => 'Le Nostre Storie', 'hero_title_en' => 'Our Stories',
                'hero_subtitle' => 'Memoria e tradizioni', 'hero_subtitle_en' => 'Memory and traditions',
                'intro_title' => 'Archivio Comunitario', 'intro_title_en' => 'Community Archive',
                'intro_text' => '<p>Raccolta di testimonianze e storie locali per mantenere viva la memoria del paese.</p>',
                'intro_text_en' => '<p>Collection of testimonies and local stories to keep the memory of the village alive.</p>',
                'hero_image' => 'ArabataHome.jpg'
            ],
            'contatti' => [
                'hero_title' => 'Contatti', 'hero_title_en' => 'Contacts',
                'hero_subtitle' => 'Siamo a tua disposizione', 'hero_subtitle_en' => 'We are at your disposal',
                'intro_title' => 'Siamo qui per te', 'intro_title_en' => 'We are here for you',
                'intro_text' => '<p>La Pro Loco di Pietrapertosa è sempre a disposizione per aiutarti a pianificare la tua visita. Non esitare a contattarci per qualsiasi informazione su eventi, escursioni e accoglienza.</p>',
                'intro_text_en' => '<p>The Pro Loco of Pietrapertosa is always available to help you plan your visit. Do not hesitate to contact us for any information on events, excursions and hospitality.</p>',
                'hero_image' => 'PietrapertosaEventi.png'
            ],
            'notizie' => [
                'hero_title' => 'Ultime Notizie', 'hero_title_en' => 'Latest News',
                'hero_subtitle' => 'Rimani aggiornato', 'hero_subtitle_en' => 'Stay updated',
                'intro_title' => 'Avvisi e comunicazioni', 'intro_title_en' => 'Notices and communications',
                'hero_image' => 'ArabataHome.jpg'
            ],
            'eventi' => [
                'hero_title' => 'Eventi e Appuntamenti', 'hero_title_en' => 'Events and Appointments',
                'hero_subtitle' => 'Vivi il borgo', 'hero_subtitle_en' => 'Experience the village',
                'hero_image' => 'PietrapertosaEventi.png'
            ],
            'galleria' => [
                'hero_title' => 'Archivio Fotografico', 'hero_title_en' => 'Photo Archive',
                'hero_subtitle' => 'Il borgo in immagini', 'hero_subtitle_en' => 'The village in pictures',
                'hero_image' => 'immaginePaese.jpg'
            ]
        ];

        foreach ($pagesData as $slug => $data) {
            $page = PageSetting::firstOrCreate(['page_slug' => $slug]);

            // Se l'immagine hero esiste ed è locale, creiamo o cerchiamo il record Media
            $mediaId = null;
            if (isset($data['hero_image'])) {
                $url = url('images/' . $data['hero_image']);
                $media = Media::firstOrCreate(
                    ['url' => $url],
                    [
                        'file_name' => $data['hero_image'],
                        'mime_type' => 'image/jpeg',
                        'size' => 0,
                        'public_id' => 'local_' . $data['hero_image'],
                        'resource_type' => 'image',
                        'provider' => 'local'
                    ]
                );
                $mediaId = $media->id;
            }

            $page->update([
                'hero_title' => $data['hero_title'] ?? $page->hero_title,
                'hero_title_en' => $data['hero_title_en'] ?? $page->hero_title_en,
                'hero_subtitle' => $data['hero_subtitle'] ?? $page->hero_subtitle,
                'hero_subtitle_en' => $data['hero_subtitle_en'] ?? $page->hero_subtitle_en,
                'intro_title' => $data['intro_title'] ?? $page->intro_title,
                'intro_title_en' => $data['intro_title_en'] ?? $page->intro_title_en,
                'intro_text' => $data['intro_text'] ?? $page->intro_text,
                'intro_text_en' => $data['intro_text_en'] ?? $page->intro_text_en,
                'hero_cta_text' => $data['hero_cta_text'] ?? $page->hero_cta_text,
                'hero_cta_url' => $data['hero_cta_url'] ?? $page->hero_cta_url,
                'data' => $data['data'] ?? $page->data,
                'hero_media_id' => $mediaId ?? $page->hero_media_id,
                'translation_status' => 'reviewed'
            ]);

            $this->info("Pagina {$slug} aggiornata.");
        }

        $this->info('Tutte le pagine sono state popolate con successo!');
    }
}
