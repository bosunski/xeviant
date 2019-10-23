<?php


namespace App\Domain\Notebook;


use Illuminate\Support\Str;

class NotebookFactory
{
    public function create(string $type, string $path, string $notebookId): ?AbstractNotebook
    {
        if (!method_exists($this, $method = sprintf("create%sNotebook", Str::ucfirst($type)))) {
            return $this->{$method}(notebook_path($path), $notebookId);
        }
    }

    public function createLaravelNotebook(string $path, string $notebookId)
    {
        return new LaravelNotebook($path, $notebookId);
    }
}
