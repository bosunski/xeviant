<?php

namespace App\Actions;

use App\Domain\Notebook\AbstractNotebook;
use App\Domain\Notebook\LaravelNotebook;
use App\Events\NotebookApplicationCreated;
use App\Events\NotebookApplicationStarted;
use Illuminate\Support\Str;

class NotebookAction
{
    private $path;
    private $data;

    public function provision($path, $data)
    {
        $path = notebook_path($path);

        $this->path = $path;
        $this->data = $data;

        if ($type = $data['type']) {
            if (method_exists($this, $method = sprintf("create%sNotebook", Str::ucfirst($type)))) {
                $this->{$method}($path, $data);
            }
        }
    }

    public function createLaravelNotebook(string $path, $data = [])
    {
        $notebook = new LaravelNotebook($path, $data['id']);

        $notebook->createNotebook(function (AbstractNotebook $notebook) {
            event(new NotebookApplicationCreated($notebook));

            $notebook->startNotebook(function (AbstractNotebook $notebook) {
                event(new NotebookApplicationStarted($notebook));
            });
        });
    }
}
