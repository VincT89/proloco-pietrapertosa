<?php

namespace Database\Seeders;

use App\Models\DirectoryItem;
use App\Models\Event;
use App\Models\Media;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // EVENTI ANNUALI
        $eventiAnnuali = [
            [
                'attributes' => [
                    'category' => 'eventi_annuali',
                    'title' => 'Sulle Tracce degli Arabi',
                    'subtitle' => 'Agosto - Rione Arabata',
                    'description' => 'Una suggestiva rievocazione storica che celebra le origini del borgo, risalenti all\'838 d.C., quando il re Bomar scelse queste alture come roccaforte. Per l\'occasione, il rione più antico del paese, l\'Arabata (a Ravt), si trasforma in un immersivo villaggio orientale tra percorsi sensoriali, profumi d\'incenso, danze del ventre e sapori speziati che si fondono con la tradizione lucana.',
                ],
                'media' => ['/images/arabata.jpeg', '/images/arabata01.jpg', '/images/panorama.jpg'],
            ],
            [
                'attributes' => [
                    'category' => 'eventi_annuali',
                    'title' => 'Sapori d\'Autunno',
                    'subtitle' => 'Fine Ottobre / Novembre - Centro Storico',
                    'description' => 'Una mostra mercato immersa nei caldi colori autunnali, dedicata alla scoperta dell\'enogastronomia e dei saperi di Pietrapertosa. I visitatori possono degustare piatti della tradizione a km 0, come la tipica pasta fatta in casa \'Manata\', formaggi e insaccati locali, assistendo a dimostrazioni di artigianato che mantengono vivo il patrimonio immateriale delle generazioni passate.',
                ],
                'media' => ['/images/sapori_hero.png', '/images/castello.jpg', '/images/immaginePaese.png'],
            ],
            [
                'attributes' => [
                    'category' => 'eventi_annuali',
                    'title' => 'U Masc\' (Il Maggio)',
                    'subtitle' => 'Giugno (dopo Sant\'Antonio) - Montepiano / Centro',
                    'description' => 'Un antichissimo rito arboreo che unisce folklore pagano e devozione. La festa celebra il \'Matrimonio degli alberi\': un robusto tronco di cerro (lo Sposo, il Mascio) viene unito a una cima di agrifoglio (la Sposa). Dopo un pittoresco trasporto in paese trainato da buoi addobbati a festa, l\'albero viene innalzato a forza di braccia, culminando con la spettacolare scalata acrobatica dei \'maggiaioli\'.',
                ],
                'media' => ['/images/sanGiacomo.jpg', '/images/campanileSanGiacomo.jpg'],
            ],
            [
                'attributes' => [
                    'category' => 'eventi_annuali',
                    'title' => 'Borgo Festival',
                    'subtitle' => 'Mesi Estivi - Piazze di Pietrapertosa',
                    'description' => 'Un contenitore di eventi culturali, musicali e di intrattenimento che animano le sere d\'estate. Le antiche piazze in pietra si riempiono di lucine, note e convivialità, offrendo a residenti e turisti un palcoscenico a cielo aperto dove vivere l\'atmosfera magica delle Dolomiti Lucane sotto le stelle.',
                ],
                'media' => ['/images/PietrapertosaEventi.png', '/images/panorama.jpg'],
            ],
        ];

        foreach ($eventiAnnuali as $ev) {
            $item = DirectoryItem::updateOrCreate(['title' => $ev['attributes']['title']], $ev['attributes']);
            $this->attachGallery($item, $ev['media']);
        }

        // COMUNITÀ
        $comunita = [
            [
                'attributes' => [
                    'category' => 'comunita',
                    'title' => 'Associazione Musicale "I Suoni delle Dolomiti"',
                    'subtitle' => 'Banda Musicale',
                    'description' => 'La voce storica del paese. Fondata decenni fa, accompagna tutte le festività locali...',
                ],
                'media' => ['/images/PietrapertosaEventi.png', '/images/arabata.jpeg', '/images/sapori_hero.png'],
            ],
            [
                'attributes' => [
                    'category' => 'comunita',
                    'title' => 'Protezione Civile',
                    'subtitle' => 'Soccorso e Assistenza',
                    'description' => 'Volontari instancabili impegnati quotidianamente nella salvaguardia del nostro fragile territorio montano.',
                ],
                'media' => ['/images/protezione_civile.png', '/images/sanCataldo.jpg', '/images/percorsi.jpg'],
            ],
        ];
        foreach ($comunita as $c) {
            $item = DirectoryItem::updateOrCreate(['title' => $c['attributes']['title']], $c['attributes']);
            $this->attachGallery($item, $c['media']);
        }

        // TERRITORIO
        $item = DirectoryItem::updateOrCreate(['title' => 'Azienda Agricola Pietra'], [
            'category' => 'territorio_aziende',
            'subtitle' => 'Produttore Locale',
            'description' => 'Coltivazione di ulivi e vigne sulle pendici del borgo.',
            'contact_info' => 'Via del Sole, 12',
        ]);
        $this->attachGallery($item, ['/images/pietrapertosaHome.jpg', '/images/PietrapertosaScopri.jpg', '/images/panorama.jpg']);

        $item = DirectoryItem::updateOrCreate(['title' => 'Gusto Lucano'], [
            'category' => 'territorio_foodtruck',
            'subtitle' => 'Food Truck',
            'description' => 'Lo street food paesano.',
            'contact_info' => 'Piazza del Carmine',
        ]);
        $this->attachGallery($item, ['/images/sapori_hero.png', '/images/pietrapertosaHome2.jpg', '/images/PietrapertosaScopri.jpg']);

        $item = DirectoryItem::updateOrCreate(['title' => 'L\'Arte del Legno'], [
            'category' => 'territorio_artigiani',
            'subtitle' => 'Bottega Artigiana',
            'description' => 'Una bottega dove il legno e la ceramica prendono vita.',
            'contact_info' => 'Corso Umberto I',
        ]);
        $this->attachGallery($item, ['/images/castello.jpg', '/images/torre.jpg', '/images/immaginePaese.png']);

        // SAPORI
        $item = DirectoryItem::updateOrCreate(['title' => 'Sapori d\'Autunno'], [
            'category' => 'sapori_piatti',
            'description' => 'L\'evento enogastronomico dedicato alla degustazione di castagne...',
        ]);
        $this->attachGallery($item, ['/images/sapori_hero.png']);
        
        $item = DirectoryItem::updateOrCreate(['title' => 'Piatti Tipici'], [
            'category' => 'sapori_piatti',
            'description' => 'Strascinati col sugo, carni lente al forno a legna.',
        ]);
        $this->attachGallery($item, ['/images/PietrapertosaScopri.jpg']);

        // 1 EVENTO DINAMICO FITTIZIO PER TESTARE "EVENTI"
        $event = Event::updateOrCreate(['title' => 'Festa Patronale di San Giacomo (Test)'], [
            'slug' => 'festa-patronale-san-giacomo-test',
            'start_date' => Carbon::today()->addDays(10),
            'end_date' => Carbon::today()->addDays(12),
            'description' => 'Processioni religiose e festeggiamenti civili.',
            'location' => 'Centro Storico',
            'status' => 'published',
        ]);
        $this->attachGallery($event, ['/images/sanGiacomo.jpg', '/images/campanileSanGiacomo.jpg']);
    }

    protected function attachGallery($model, $urls)
    {
        if (! $model) {
            return;
        }
        $mediaIds = [];
        foreach ($urls as $url) {
            $media = Media::firstOrCreate(['public_id' => 'dummy_'.md5($url)], [
                'url' => $url,
                'resource_type' => 'image',
            ]);
            $mediaIds[] = $media->id;
        }
        $syncData = [];
        foreach ($mediaIds as $index => $id) {
            $syncData[$id] = ['order' => $index];
        }
        $model->galleryMedia()->sync($syncData);

        if (Schema::hasColumn($model->getTable(), 'cover_media_id') && ! $model->cover_media_id && count($mediaIds) > 0) {
            $model->cover_media_id = $mediaIds[0];
            $model->save();
        }
    }
}
