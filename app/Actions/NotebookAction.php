<?php

namespace App\Actions;

use App\Domain\Notebook\AbstractNotebook;
use App\Domain\Notebook\LaravelNotebook;
use App\Domain\Notebook\NotebookFactory;
use App\Events\NotebookApplicationCreated;
use App\Events\NotebookApplicationStarted;
use Illuminate\Support\Str;

class NotebookAction
{
    private $notebookFactory;

    public function __construct()
    {
        $this->notebookFactory = new NotebookFactory;
    }

    public function startNotebookApplication($path, $data)
    {
        tap($this->createNotebook($path, $data), function (?AbstractNotebook $notebook) {
            $notebook->startNotebook(function (AbstractNotebook $notebook) {
                event(new NotebookApplicationStarted($notebook));
            });
        });
    }

    public function provision($path, $data)
    {
        if ($type = $data['type']) {
            tap($this->createNotebook($path, $data), function (?AbstractNotebook $notebook) {
                if ($notebook) {
                    $notebook->createNotebook(function (AbstractNotebook $notebook) {
                        event(new NotebookApplicationCreated($notebook));

                        $notebook->startNotebook(function (AbstractNotebook $notebook) {
                            event(new NotebookApplicationStarted($notebook));
                        });
                    });
                }
            });
        }
    }

    public function createNotebook(string $path, array $data = []): ?AbstractNotebook
    {
        return $this->notebookFactory->create($data['type'], $path, $data['id']);
    }
}
