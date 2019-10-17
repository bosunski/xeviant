<?php

namespace App;

class Notebook
{
    /**
     * @var string
     */
    private $notebookPath;

    public function __construct(string $notebookPath)
    {
        $this->notebookPath = $notebookPath;
    }

    public function getNotebookFilePath(): string
    {
        return $this->getNotebookDirectory() . DIRECTORY_SEPARATOR . $this->getFileName();
    }

    public function getNotebookDirectory(): string
    {
        return notebook_path($this->notebookPath);
    }

    public function getFileName(): string
    {
        return explode('/', $this->notebookPath)[1] . ".php";
    }
}
