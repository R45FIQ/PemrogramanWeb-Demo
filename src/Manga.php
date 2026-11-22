<?php

namespace FlipPlay;

require_once __DIR__ . '/Media.php';

class Manga extends Media
{
    private int $chapters;
    private string $author;

    public function __construct(
        string $id,
        string $title,
        string $genre,
        float $rating,
        int $releaseYear,
        int $chapters,
        string $author
    ) {
        parent::__construct($id, $title, $genre, $rating, $releaseYear);
        $this->chapters = $chapters;
        $this->author = $author;
    }

    public function getChapters(): int
    {
        return $this->chapters;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getType(): string
    {
        return "Manga";
    }

    public function getDetails(): string
    {
        return "[MANGA] {$this->title} ({$this->releaseYear})\n"
             . "  - ID          : {$this->id}\n"
             . "  - Genre       : {$this->genre}\n"
             . "  - Rating      : {$this->rating} / 10.0 ★\n"
             . "  - Total Ch.   : {$this->chapters} Chapter\n"
             . "  - Mangaka     : {$this->author}";
    }
}
