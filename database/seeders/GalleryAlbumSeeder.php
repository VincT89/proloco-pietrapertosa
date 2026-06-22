<?php

namespace Database\Seeders;

use App\Models\GalleryAlbum;
use App\Models\Media;
use Illuminate\Database\Seeder;

class GalleryAlbumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. L'album preso dal vecchio database (MySQL backup)
        $album1 = GalleryAlbum::firstOrCreate(
            ['title' => 'test 2026'],
            [
                'title_en' => null,
                'section_date' => null,
                'sort_order' => 0,
                'created_at' => '2026-06-08 08:37:30',
                'updated_at' => '2026-06-10 09:31:29',
            ]
        );

        $urls1 = [
            'https://res.cloudinary.com/dn8nhmtch/image/upload/v1780898352/proloco/gallery/hisednzzwaiholpvdikm.jpg',
            'https://res.cloudinary.com/dn8nhmtch/image/upload/v1780897319/proloco/gallery/frqfk2vew8vxuyv5mrws.jpg',
            'https://res.cloudinary.com/dn8nhmtch/image/upload/v1780897319/proloco/gallery/s4wsvb0qsmmcxpaqgmgr.jpg',
        ];
        $this->attachGallery($album1, $urls1);

        // 2. Le 6 foto fallback dell'archivio storico (precedentemente mostrate)
        $album2 = GalleryAlbum::firstOrCreate(
            ['title' => 'Archivio Storico'],
            [
                'title_en' => 'Historical Archive',
                'section_date' => '2025-01-01',
                'sort_order' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $urls2 = [
            '/images/immaginePaese.jpg',
            '/images/arabata.jpeg',
            '/images/castello.jpg',
            '/images/percorsi.jpg',
            '/images/voloAngelo.jpg',
            '/images/viaFerrata.jpg',
        ];
        $this->attachGallery($album2, $urls2);
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
    }
}
