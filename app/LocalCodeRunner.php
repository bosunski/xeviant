<?php

namespace App;

use App\Contract\ProcessRunner;
use Exception;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
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

        $process->on('exit', function($exitCode) use ($notebookPath, $handler) {
            call_user_func_array([$handler, 'exitHandler'], [$exitCode]);

            fetch(env('LARAVEL_APP_URL') . '/evaluated/' . Str::after($notebookPath, '/'))
                ->then(function (ResponseInterface $response) {
                    //
                }, function (Exception $e) {
                    var_dump($e->getMessage());
                });
    });

        $loop->addTimer(2.0, [$handler, 'timeoutHandler']);
    }
}
