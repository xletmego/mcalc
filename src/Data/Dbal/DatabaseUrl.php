<?php declare(strict_types=1);

namespace App\Data\Dbal;

final class DatabaseUrl
{
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function toString():string
    {
        return $this->url;
    }
}
