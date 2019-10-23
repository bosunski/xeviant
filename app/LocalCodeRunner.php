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
    public static function run(Process $process, $notebookId = null)
    {
        $handler = new ProcessOutputAggregator($process, $notebookId);
        $process->start($loop = app(LoopInterface::class));

        $process->stdout->on('data', [$handler, 'outputHandler']);
        $process->stdout->on('error', [$handler, 'outputHandler']);

        $process->on('exit', function($exitCode) use ($notebookId, $handler) {
            call_user_func_array([$handler, 'exitHandler'], [$exitCode]);

            fetch(env('LARAVEL_APP_URL') . '/evaluated/' . $notebookId)
                ->then(function (ResponseInterface $response) {
                    //
                }, function (Exception $e) {
                    var_dump($e->getMessage());
                });

            echo "Done";
    });

        $loop->addTimer(2.0, [$handler, 'timeoutHandler']);
    }
}
