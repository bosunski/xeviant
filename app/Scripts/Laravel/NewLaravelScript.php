<?php


namespace App\Scripts\Laravel;


use App\Scripts\Script;
use Illuminate\Support\Str;

class NewLaravelScript extends Script
{
    /**
     * @var string
     */
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function script(): string
    {
        $base = basename($this->path);

        $baseDirectory = Str::before($this->path, $base);

        echo "Running: ", "$(which laravel) new $this->path";

        return "
            mkdir -p $baseDirectory &&
            cd $baseDirectory &&
            $(which laravel) new $base --force
        ";
    }

    static function make(string $path): NewLaravelScript
    {
        return new static($path);
    }
}
