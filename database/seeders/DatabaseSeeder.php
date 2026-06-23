<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = env('ADMIN_PASSWORD');

        if (! $password) {
            throw new \RuntimeException('ADMIN_PASSWORD non impostata nel file .env.');
        }

        User::factory()->create([
            'name' => 'Admin',
            'email' => env('ADMIN_EMAIL', 'admin@prolocopietrapertosana.it'),
            'password' => Hash::make($password),
        ]);

        // $this->call([
        //     ContentSeeder::class,
        //     GalleryAlbumSeeder::class,
        // ]);
    }
}
