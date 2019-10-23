<?php


namespace App\Scripts\Laravel;


use App\Scripts\Script;

class ArtisanServe extends Script
{
    /**
     * @var string
     */
    private $path;
    /**
     * @var int
     */
    private $port;

    public function __construct(string $path, int $port)
    {
        $this->path = $path;
        $this->port = $port;
    }

    public function script(): string
    {
        return "$(which php) $this->path/artisan serve --port=$this->port";
    }
}
