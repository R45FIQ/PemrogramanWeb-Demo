<?php

namespace App\Http\Controllers;

use App\Http\Resources\MediaResource;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class UserCollectionController extends Controller
{
    /**
     * Menampilkan seluruh media yang ada di dalam koleksi / watchlist pengguna.
     */
    public function index(User $user): AnonymousResourceCollection
    {
        // Ambil seluruh media milik user dengan eager loading jika dibutuhkan
        $collection = $user->media()->get();

        return MediaResource::collection($collection);
    }

    /**
     * Menambahkan media ke dalam koleksi pengguna (Many-to-Many attach).
     * Menggunakan transaksi dan lockForUpdate untuk mencegah race condition.
     */
    public function store(User $user, Media $media): JsonResponse
    {
        return DB::transaction(function () use ($user, $media) {
            // Periksa apakah media sudah ada di dalam koleksi pengguna
            $exists = $user->media()
                ->where('media_id', $media->id)
                ->lockForUpdate()
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'Media sudah ada di dalam koleksi pengguna.',
                ], 409); // 409 Conflict
            }

            // Tambahkan media ke pivot table media_user
            $user->media()->attach($media->id);

            return response()->json([
                'message' => 'Media berhasil ditambahkan ke koleksi pengguna.',
                'data' => new MediaResource($media),
            ], 201); // 201 Created
        });
    }

    /**
     * Menghapus media dari koleksi pengguna (Many-to-Many detach).
     */
    public function destroy(User $user, Media $media): JsonResponse
    {
        return DB::transaction(function () use ($user, $media) {
            // Periksa apakah media memang ada di dalam koleksi pengguna
            $exists = $user->media()
                ->where('media_id', $media->id)
                ->lockForUpdate()
                ->exists();

            if (!$exists) {
                return response()->json([
                    'message' => 'Media tidak ditemukan di dalam koleksi pengguna.',
                ], 404); // 404 Not Found
            }

            // Hapus relasi pivot tanpa menghapus record media dari tabel media
            $user->media()->detach($media->id);

            return response()->json([
                'message' => 'Media berhasil dihapus dari koleksi pengguna.',
            ], 200);
        });
    }
}
