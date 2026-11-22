<?php

namespace FlipPlay;

require_once __DIR__ . '/Media.php';

class User
{
    private string $userId;
    private string $name;
    private string $email;
    private array $watchlist = [];

    public function __construct(string $userId, string $name, string $email)
    {
        $this->userId = $userId;
        $this->name = $name;
        $this->email = $email;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function addToWatchlist(Media $media): bool
    {
        foreach ($this->watchlist as $item) {
            if ($item->getId() === $media->getId()) {
                return false;
            }
        }

        $this->watchlist[] = $media;
        return true;
    }

    public function getWatchlist(): array
    {
        return $this->watchlist;
    }

    public function displayWatchlist(): void
    {
        echo "====================================================================\n";
        echo " 👤 KOLEKSI WATCHLIST: {$this->name} ({$this->email})\n";
        echo "====================================================================\n";

        if (empty($this->watchlist)) {
            echo " (Belum ada media yang ditambahkan ke dalam watchlist)\n";
            echo "====================================================================\n";
            return;
        }

        $no = 1;
        foreach ($this->watchlist as $media) {
            printf(
                " [%d] [%-6s] %-28s | Rating: %3.1f★ | %d\n",
                $no++,
                $media->getType(),
                $media->getTitle(),
                $media->getRating(),
                $media->getReleaseYear()
            );
        }
        echo " Total: " . count($this->watchlist) . " media tersimpan di watchlist.\n";
        echo "====================================================================\n";
    }
}
