<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMediaRequest;
use App\Http\Requests\UpdateMediaRequest;
use App\Http\Resources\MediaResource;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MediaController extends Controller
{
    /**
     * Menampilkan daftar seluruh media dengan pagination, pencarian, dan filter.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Media::query();

        // Pencarian berdasarkan judul menggunakan query parameter search
        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where('title', 'like', "%{$search}%");
        }

        // Filter berdasarkan jenis media (Anime, Manga, Drama)
        if ($request->filled('type')) {
            $query->where('type', $request->query('type'));
        }

        // Filter berdasarkan genre
        if ($request->filled('genre')) {
            $query->where('genre', 'like', '%' . $request->query('genre') . '%');
        }

        $perPage = (int) $request->query('per_page', 10);
        $mediaList = $query->latest('id')->paginate($perPage);

        return MediaResource::collection($mediaList);
    }

    /**
     * Menambahkan media baru ke dalam database.
     */
    public function store(StoreMediaRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $media = Media::create($validated);

        return (new MediaResource($media))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Menampilkan detail satu media beserta relasi pengguna yang mengoleksinya (eager loading).
     */
    public function show(Media $media): MediaResource
    {
        // Eager load relasi users untuk menghindari N+1 query problem
        $media->load('users');

        return new MediaResource($media);
    }

    /**
     * Memperbarui data media berdasarkan ID (mendukung PUT dan partial PATCH).
     */
    public function update(UpdateMediaRequest $request, Media $media): JsonResponse
    {
        $validated = $request->validated();
        $media->update($validated);

        return (new MediaResource($media->fresh()))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Menghapus media dari database dan membersihkan relasi pivot terkait.
     */
    public function destroy(Media $media): JsonResponse
    {
        // Bersihkan relasi pivot terlebih dahulu sebelum menghapus media
        $media->users()->detach();
        $media->delete();

        return response()->json([
            'message' => 'Media berhasil dihapus dari sistem.',
        ], 200);
    }
}
