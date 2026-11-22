<?php

namespace FlipPlay;

require_once __DIR__ . '/Media.php';

class FlipPlayApp
{
    private array $mediaCatalog = [];

    public function __construct()
    {
        $this->mediaCatalog = [];
    }

    public function addMedia(Media $media): void
    {
        $this->mediaCatalog[$media->getId()] = $media;
    }

    public function getAllMedia(): array
    {
        return array_values($this->mediaCatalog);
    }

    public function countMedia(): int
    {
        return count($this->mediaCatalog);
    }

    public function displayAllMedia(): void
    {
        echo "====================================================================\n";
        echo " 🎬 KATALOG MEDIA FLIPPLAY (ALL-IN-ONE ENTERTAINMENT)\n";
        echo "====================================================================\n";
        printf(" %-5s | %-6s | %-26s | %-12s | %-6s\n", "ID", "TIPE", "JUDUL MEDIA", "GENRE", "RATING");
        echo "--------------------------------------------------------------------\n";

        if (empty($this->mediaCatalog)) {
            echo " (Katalog masih kosong)\n";
            echo "====================================================================\n";
            return;
        }

        foreach ($this->mediaCatalog as $media) {
            printf(
                " %-5s | %-6s | %-26s | %-12s | %4.1f ★\n",
                $media->getId(),
                $media->getType(),
                $media->getTitle(),
                $media->getGenre(),
                $media->getRating()
            );
        }
        echo "====================================================================\n";
        echo " Total Media Tersedia: " . $this->countMedia() . " judul.\n";
        echo "====================================================================\n";
    }

    public function searchByTitle(string $query): array
    {
        $results = [];
        $queryLower = strtolower(trim($query));

        foreach ($this->mediaCatalog as $media) {
            if (str_contains(strtolower($media->getTitle()), $queryLower)) {
                $results[] = $media;
            }
        }

        return $results;
    }

    public function findMediaById(string $id): ?Media
    {
        return $this->mediaCatalog[$id] ?? null;
    }
}
