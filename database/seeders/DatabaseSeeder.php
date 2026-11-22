<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with FlipPlay Modul 3 & 4 data.
     */
    public function run(): void
    {
        // 1. Seed Pengguna (Termasuk Pengguna dari Modul 3)
        $user1 = User::create([
            'name' => 'Citra Maharani',
            'email' => 'citra@student.umm.ac.id',
            'password' => Hash::make('password123'),
        ]);

        $user2 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@student.umm.ac.id',
            'password' => Hash::make('password123'),
        ]);

        // 2. Seed Katalog Media (Dari Implementasi Modul 3)
        $aot = Media::create([
            'title' => 'Attack on Titan',
            'type' => 'Anime',
            'genre' => 'Dark Fantasy',
            'rating' => 9.0,
            'release_year' => 2013,
            'episodes' => 89,
            'studio' => 'Wit Studio & MAPPA',
            'description' => 'Perjuangan umat manusia mempertahankan diri dari ancaman para raksasa Titan di dalam tembok raksasa.',
        ]);

        $frieren = Media::create([
            'title' => 'Frieren: Beyond Journey',
            'type' => 'Anime',
            'genre' => 'Adventure',
            'rating' => 9.1,
            'release_year' => 2023,
            'episodes' => 28,
            'studio' => 'Madhouse',
            'description' => 'Petualangan Frieren seorang penyihir elf yang memulai perjalanan baru memahami arti kehidupan manusia.',
        ]);

        $overlord = Media::create([
            'title' => 'Overlord',
            'type' => 'Anime',
            'genre' => 'Isekai Fantasy',
            'rating' => 7.9,
            'release_year' => 2015,
            'episodes' => 52,
            'studio' => 'Madhouse',
            'description' => 'Momonga terjebak dalam game online DMMO-RPG dan memimpin Great Tomb of Nazarick menaklukkan dunia baru.',
        ]);

        $onepiece = Media::create([
            'title' => 'One Piece',
            'type' => 'Manga',
            'genre' => 'Action Shounen',
            'rating' => 9.2,
            'release_year' => 1997,
            'chapters' => 1110,
            'author' => 'Eiichiro Oda',
            'description' => 'Petualangan bajak laut Topi Jerami dipimpin Monkey D. Luffy untuk menemukan harta karun legendaris One Piece.',
        ]);

        $jjk = Media::create([
            'title' => 'Jujutsu Kaisen',
            'type' => 'Manga',
            'genre' => 'Supernatural',
            'rating' => 8.5,
            'release_year' => 2018,
            'chapters' => 271,
            'author' => 'Gege Akutami',
            'description' => 'Yuji Itadori menelan jari kutukan Sukuna dan masuk ke SMA Jujutsu demi melawan kutukan berbahaya.',
        ]);

        $aib = Media::create([
            'title' => 'Alice in Borderland',
            'type' => 'Drama',
            'genre' => 'Suspense',
            'rating' => 8.6,
            'release_year' => 2020,
            'episodes' => 16,
            'country' => 'Jepang',
            'description' => 'Arisu dan teman-temannya terlempar ke Tokyo versi paralel dan terpaksa memainkan game berbahaya demi bertahan hidup.',
        ]);

        $itaewon = Media::create([
            'title' => 'Itaewon Class',
            'type' => 'Drama',
            'genre' => 'Drama Romance',
            'rating' => 8.2,
            'release_year' => 2020,
            'episodes' => 16,
            'country' => 'Korea Selatan',
            'description' => 'Park Sae-ro-yi berjuang mendirikan bar restoran DanBam di jalanan Itaewon untuk membalaskan dendamnya.',
        ]);

        // 3. Seed Koleksi Awal Milik Citra Maharani (Sesuai Simulasi Modul 3)
        $user1->media()->attach([$aot->id, $onepiece->id]);
    }
}
