<?php

namespace FlipPlay;

require_once __DIR__ . '/Media.php';

class Drama extends Media
{
    private int $episodes;
    private string $country;

    public function __construct(
        string $id,
        string $title,
        string $genre,
        float $rating,
        int $releaseYear,
        int $episodes,
        string $country
    ) {
        parent::__construct($id, $title, $genre, $rating, $releaseYear);
        $this->episodes = $episodes;
        $this->country = $country;
    }

    public function getEpisodes(): int
    {
        return $this->episodes;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getType(): string
    {
        return "Drama";
    }

    public function getDetails(): string
    {
        return "[DRAMA] {$this->title} ({$this->releaseYear})\n"
             . "  - ID          : {$this->id}\n"
             . "  - Genre       : {$this->genre}\n"
             . "  - Rating      : {$this->rating} / 10.0 ★\n"
             . "  - Total Eps   : {$this->episodes} Episode\n"
             . "  - Negara Asal : {$this->country}";
    }
}
