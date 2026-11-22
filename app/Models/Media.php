<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Media extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     *
     * @var string
     */
    protected $table = 'media';

    /**
     * Kolom-kolom yang dapat diisi melalui mass-assignment.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'type',
        'genre',
        'rating',
        'release_year',
        'description',
        'episodes',
        'chapters',
        'author',
        'studio',
        'country',
    ];

    /**
     * Format casting tipe data atribut model.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'float',
        'release_year' => 'integer',
        'episodes' => 'integer',
        'chapters' => 'integer',
    ];

    /**
     * Relasi Many-to-Many ke model User (pengguna yang menyimpan media ke koleksi/watchlist).
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'media_user', 'media_id', 'user_id')
            ->withTimestamps();
    }
}
