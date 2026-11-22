<?php

namespace FlipPlay;

require_once __DIR__ . '/Media.php';

class Anime extends Media
{
    private int $episodes;
    private string $studio;

    public function __construct(
        string $id,
        string $title,
        string $genre,
        float $rating,
        int $releaseYear,
        int $episodes,
        string $studio
    ) {
        parent::__construct($id, $title, $genre, $rating, $releaseYear);
        $this->episodes = $episodes;
        $this->studio = $studio;
    }

    public function getEpisodes(): int
    {
        return $this->episodes;
    }

    public function getStudio(): string
    {
        return $this->studio;
    }

    public function getType(): string
    {
        return "Anime";
    }

    public function getDetails(): string
    {
        return "[ANIME] {$this->title} ({$this->releaseYear})\n"
             . "  - ID          : {$this->id}\n"
             . "  - Genre       : {$this->genre}\n"
             . "  - Rating      : {$this->rating} / 10.0 ★\n"
             . "  - Total Eps   : {$this->episodes} Episode\n"
             . "  - Studio      : {$this->studio}";
    }
}
