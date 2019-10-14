<?php

namespace App\Http\Responders;

use React\EventLoop\LoopInterface;
use React\Filesystem\Filesystem;

class NotebookResponder
{
    public function __invoke()
    {
        $filesystem = Filesystem::create($loop = app()->get(LoopInterface::class));
        $fs = yield $filesystem->dir(__DIR__)->ls();

        foreach ($fs as $node) {
            var_dump($node->getPath());
        }

        return "Hello WorldXX! %%%";
    }
}
