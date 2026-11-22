<?php

namespace FlipPlay;

class Media
{
    protected string $id;
    protected string $title;
    protected string $genre;
    protected float $rating;
    protected int $releaseYear;

    public function __construct(string $id, string $title, string $genre, float $rating, int $releaseYear)
    {
        $this->id = $id;
        $this->title = $title;
        $this->genre = $genre;
        $this->rating = $rating;
        $this->releaseYear = $releaseYear;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getGenre(): string
    {
        return $this->genre;
    }

    public function getRating(): float
    {
        return $this->rating;
    }

    public function getReleaseYear(): int
    {
        return $this->releaseYear;
    }

    public function getType(): string
    {
        return "Media";
    }

    public function getDetails(): string
    {
        return "ID: {$this->id} | Judul: {$this->title} | Genre: {$this->genre} | Rating: {$this->rating}★ | Tahun: {$this->releaseYear}";
    }
}
