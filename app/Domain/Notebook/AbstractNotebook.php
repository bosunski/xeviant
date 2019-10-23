<?php

namespace App\Domain\Notebook;

use React\EventLoop\LoopInterface;

abstract  class AbstractNotebook
{
    protected $notebookId;

    protected $path;

    /**
     * @var LoopInterface
     */
    protected $loop;

    /**
     * @var int
     */
    protected $port = null;

    public function __construct(string $path, $notebookId)
    {
        $this->loop = app(LoopInterface::class);
        $this->path = $path;
        $this->notebookId = $notebookId;
    }

    abstract public function startNotebook(callable $onStart = null);
    abstract public function createNotebook(callable $onCreated = null);

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort(int $port): void
    {
        $this->port = $port;
    }

    /**
     * @return mixed
     */
    public function getNotebookId()
    {
        return $this->notebookId;
    }
}
