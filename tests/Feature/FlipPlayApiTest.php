<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlipPlayApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** 1. GET seluruh media */
    public function test_can_get_all_media(): void
    {
        $response = $this->getJson('/api/media');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'type', 'genre', 'rating', 'release_year']
                ],
                'links',
                'meta',
            ]);
    }

    /** 2. GET detail media */
    public function test_can_get_single_media_with_users(): void
    {
        $media = Media::where('title', 'Attack on Titan')->first();

        $response = $this->getJson("/api/media/{$media->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.title', 'Attack on Titan')
            ->assertJsonStructure([
                'data' => [
                    'id', 'title', 'type', 'genre', 'rating', 'release_year', 'users'
                ]
            ]);
    }

    /** 3. POST media baru */
    public function test_can_create_new_media(): void
    {
        $payload = [
            'title' => 'Solo Leveling',
            'type' => 'Anime',
            'genre' => 'Action Fantasy',
            'rating' => 8.4,
            'release_year' => 2024,
            'description' => 'Sung Jin-woo menjadi hunter terkuat di dunia setelah terbangun dari insiden dungeon ganda.',
            'episodes' => 12,
            'studio' => 'A-1 Pictures',
        ];

        $response = $this->postJson('/api/media', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'Solo Leveling')
            ->assertJsonPath('data.type', 'Anime');

        $this->assertDatabaseHas('media', ['title' => 'Solo Leveling']);
    }

    /** 4. POST dengan judul kosong */
    public function test_cannot_create_media_without_title(): void
    {
        $payload = [
            'type' => 'Anime',
            'genre' => 'Action',
        ];

        $response = $this->postJson('/api/media', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    /** 5. POST dengan judul duplikat */
    public function test_cannot_create_media_with_duplicate_title(): void
    {
        $payload = [
            'title' => 'Attack on Titan', // Sudah ada di seeder
            'type' => 'Anime',
            'genre' => 'Fantasy',
        ];

        $response = $this->postJson('/api/media', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    /** 6. PATCH memperbarui media */
    public function test_can_patch_media(): void
    {
        $media = Media::where('title', 'Overlord')->first();

        $response = $this->patchJson("/api/media/{$media->id}", [
            'rating' => 8.1,
            'description' => 'Updated deskripsi untuk Overlord.',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.rating', 8.1);

        $this->assertDatabaseHas('media', [
            'id' => $media->id,
            'rating' => 8.1,
        ]);
    }

    /** 7. PATCH dengan judul yang sudah digunakan media lain */
    public function test_cannot_patch_media_with_existing_title_of_another_media(): void
    {
        $mediaAot = Media::where('title', 'Attack on Titan')->first();
        $mediaFrieren = Media::where('title', 'Frieren: Beyond Journey')->first();

        $response = $this->patchJson("/api/media/{$mediaFrieren->id}", [
            'title' => $mediaAot->title,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    /** 8. DELETE media */
    public function test_can_delete_media(): void
    {
        $media = Media::where('title', 'Overlord')->first();

        $response = $this->deleteJson("/api/media/{$media->id}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Media berhasil dihapus dari sistem.');

        $this->assertDatabaseMissing('media', ['id' => $media->id]);
    }

    /** 9. GET media dengan ID tidak ditemukan */
    public function test_returns_404_when_media_not_found(): void
    {
        $response = $this->getJson('/api/media/99999');

        $response->assertStatus(404);
    }

    /** 10. GET media dengan pencarian judul */
    public function test_can_search_media_by_title(): void
    {
        $response = $this->getJson('/api/media?search=Titan');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Attack on Titan');
    }

    /** 11. POST menambahkan media ke koleksi */
    public function test_can_add_media_to_user_collection(): void
    {
        $user = User::where('email', 'budi@student.umm.ac.id')->first();
        $media = Media::where('title', 'Frieren: Beyond Journey')->first();

        $response = $this->postJson("/api/users/{$user->id}/collection/{$media->id}");

        $response->assertStatus(201)
            ->assertJsonPath('message', 'Media berhasil ditambahkan ke koleksi pengguna.');

        $this->assertDatabaseHas('media_user', [
            'user_id' => $user->id,
            'media_id' => $media->id,
        ]);
    }

    /** 12. POST menambahkan media yang sama untuk kedua kalinya (409 Conflict) */
    public function test_cannot_add_duplicate_media_to_user_collection(): void
    {
        // Citra Maharani sudah memiliki Attack on Titan dari seeder
        $user = User::where('email', 'citra@student.umm.ac.id')->first();
        $media = Media::where('title', 'Attack on Titan')->first();

        $response = $this->postJson("/api/users/{$user->id}/collection/{$media->id}");

        $response->assertStatus(409)
            ->assertJsonPath('message', 'Media sudah ada di dalam koleksi pengguna.');
    }

    /** 13. GET koleksi pengguna */
    public function test_can_get_user_collection(): void
    {
        $user = User::where('email', 'citra@student.umm.ac.id')->first();

        $response = $this->getJson("/api/users/{$user->id}/collection");

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data'); // AoT & One Piece
    }

    /** 14. DELETE media dari koleksi */
    public function test_can_remove_media_from_user_collection(): void
    {
        $user = User::where('email', 'citra@student.umm.ac.id')->first();
        $media = Media::where('title', 'Attack on Titan')->first();

        $response = $this->deleteJson("/api/users/{$user->id}/collection/{$media->id}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Media berhasil dihapus dari koleksi pengguna.');

        $this->assertDatabaseMissing('media_user', [
            'user_id' => $user->id,
            'media_id' => $media->id,
        ]);

        // Pastikan media itu sendiri TIDAK terhapus dari tabel media
        $this->assertDatabaseHas('media', ['id' => $media->id]);
    }

    /** 15. DELETE relasi yang tidak tersedia (404 Not Found) */
    public function test_cannot_remove_unrelated_media_from_user_collection(): void
    {
        $user = User::where('email', 'budi@student.umm.ac.id')->first(); // Budi belum punya media apapun
        $media = Media::where('title', 'Attack on Titan')->first();

        $response = $this->deleteJson("/api/users/{$user->id}/collection/{$media->id}");

        $response->assertStatus(404)
            ->assertJsonPath('message', 'Media tidak ditemukan di dalam koleksi pengguna.');
    }

    /** 16. Mengakses User atau Media dengan ID tidak ditemukan (404 Not Found) */
    public function test_returns_404_for_invalid_user_or_media_collection_route(): void
    {
        $user = User::first();

        // User valid, media tidak ditemukan
        $response1 = $this->postJson("/api/users/{$user->id}/collection/99999");
        $response1->assertStatus(404);

        // User tidak ditemukan, media valid
        $media = Media::first();
        $response2 = $this->postJson("/api/users/99999/collection/{$media->id}");
        $response2->assertStatus(404);
    }
}
