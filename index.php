<?php

require_once __DIR__ . '/src/Media.php';
require_once __DIR__ . '/src/Anime.php';
require_once __DIR__ . '/src/Manga.php';
require_once __DIR__ . '/src/Drama.php';
require_once __DIR__ . '/src/User.php';
require_once __DIR__ . '/src/FlipPlayApp.php';

use FlipPlay\Anime;
use FlipPlay\Drama;
use FlipPlay\FlipPlayApp;
use FlipPlay\Manga;
use FlipPlay\Media;
use FlipPlay\User;

echo "\n====================================================================\n";
echo "          🚀 FLIPPLAY BACKEND ENGINE SIMULATION (PHP OOP)           \n";
echo "      Platform Hiburan All-in-One: Anime, Manga, & Drama           \n";
echo "====================================================================\n";

$app = new FlipPlayApp();

echo "\n[SIMULASI 1] Menambahkan Media ke dalam Library Katalog...\n";
echo "--------------------------------------------------------------------\n";

$anime1 = new Anime("A01", "Attack on Titan", "Dark Fantasy", 9.0, 2013, 89, "Wit Studio & MAPPA");
$anime2 = new Anime("A02", "Frieren: Beyond Journey", "Adventure", 9.1, 2023, 28, "Madhouse");
$anime3 = new Anime("A03", "Overlord", "Isekai Fantasy", 7.9, 2015, 52, "Madhouse");

$manga1 = new Manga("M01", "One Piece", "Action Shounen", 9.2, 1997, 1110, "Eiichiro Oda");
$manga2 = new Manga("M02", "Jujutsu Kaisen", "Supernatural", 8.5, 2018, 271, "Gege Akutami");

$drama1 = new Drama("D01", "Alice in Borderland", "Suspense", 8.6, 2020, 16, "Jepang");
$drama2 = new Drama("D02", "Itaewon Class", "Drama Romance", 8.2, 2020, 16, "Korea Selatan");

$app->addMedia($anime1);
$app->addMedia($anime2);
$app->addMedia($anime3);
$app->addMedia($manga1);
$app->addMedia($manga2);
$app->addMedia($drama1);
$app->addMedia($drama2);

echo "✓ Berhasil menambahkan 3 Anime, 2 Manga, dan 2 Drama ke library.\n";

echo "\n[SIMULASI 2] Menampilkan Seluruh Daftar Media dalam Library...\n";
$app->displayAllMedia();

echo "\n[SIMULASI 3] Fitur Pencarian Media Berdasarkan Judul...\n";
echo "--------------------------------------------------------------------\n";

$keyword1 = "Piece";
echo "🔍 Mencari media dengan kata kunci: '{$keyword1}'\n";
$searchResult1 = $app->searchByTitle($keyword1);
if (!empty($searchResult1)) {
    foreach ($searchResult1 as $item) {
        echo "   -> Ditemukan: [{$item->getType()}] {$item->getTitle()} ({$item->getReleaseYear()}) - Rating: {$item->getRating()}★\n";
    }
} else {
    echo "   -> Tidak ditemukan media yang cocok.\n";
}

$keyword2 = "Titan";
echo "🔍 Mencari media dengan kata kunci: '{$keyword2}'\n";
$searchResult2 = $app->searchByTitle($keyword2);
if (!empty($searchResult2)) {
    foreach ($searchResult2 as $item) {
        echo "   -> Ditemukan: [{$item->getType()}] {$item->getTitle()} ({$item->getReleaseYear()}) - Rating: {$item->getRating()}★\n";
    }
} else {
    echo "   -> Tidak ditemukan media yang cocok.\n";
}

$keyword3 = "Naruto";
echo "🔍 Mencari media dengan kata kunci: '{$keyword3}'\n";
$searchResult3 = $app->searchByTitle($keyword3);
if (!empty($searchResult3)) {
    foreach ($searchResult3 as $item) {
        echo "   -> Ditemukan: [{$item->getType()}] {$item->getTitle()}\n";
    }
} else {
    echo "   -> [Hasil Kosong] Tidak ditemukan media dengan judul '{$keyword3}'.\n";
}

echo "\n[SIMULASI 4] Menampilkan Detail Spesifik Media (Konsep Polymorphism)...\n";
echo "--------------------------------------------------------------------\n";

$sampleMedia = [
    $app->findMediaById("A01"),
    $app->findMediaById("M01"),
    $app->findMediaById("D01"),
];

foreach ($sampleMedia as $media) {
    if ($media instanceof Media) {
        echo $media->getDetails() . "\n\n";
    }
}

echo "[SIMULASI 5] Mengelola Koleksi / Watchlist Milik Pengguna...\n";
echo "--------------------------------------------------------------------\n";

$user = new User("USR-2026", "Citra Maharani", "citra@student.umm.ac.id");
echo "👤 Akun Pengguna Dibuat: {$user->getName()} ({$user->getEmail()})\n\n";

echo "➕ Menambahkan media ke Watchlist pengguna:\n";

if ($user->addToWatchlist($anime1)) {
    echo "   ✓ Ditambahkan ke watchlist: [Anime] {$anime1->getTitle()}\n";
}
if ($user->addToWatchlist($manga1)) {
    echo "   ✓ Ditambahkan ke watchlist: [Manga] {$manga1->getTitle()}\n";
}
if ($user->addToWatchlist($drama1)) {
    echo "   ✓ Ditambahkan ke watchlist: [Drama] {$drama1->getTitle()}\n";
}

if (!$user->addToWatchlist($anime1)) {
    echo "   ✗ Gagal menambahkan: [Anime] {$anime1->getTitle()} (Sudah ada di dalam watchlist!)\n";
}

echo "\n";
$user->displayWatchlist();

echo "\n====================================================================\n";
echo "         🎉 SELURUH SIMULASI BACKEND FLIPPLAY BERJALAN SUKSES!       \n";
echo "====================================================================\n\n";
