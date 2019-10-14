<?php

namespace App;

use App\Contract\ProcessRunner;
use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;

class LocalCodeRunner implements ProcessRunner
{
    public static function run(Process $process, $notebookPath = null)
    {
        $handler = new ProcessOutputAggregator($process, $notebookPath);
        $process->start($loop = app(LoopInterface::class));

        $process->stdout->on('data', [$handler, 'outputHandler']);
        $process->stdout->on('error', [$handler, 'outputHandler']);

        $process->on('exit', [$handler, 'exitHandler']);

        $loop->addTimer(2.0, [$handler, 'timeoutHandler']);
    }
}
