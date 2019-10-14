<?php

namespace App;

use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;
use Xeviant\Async\Foundation\WebSocket\Session;

class ComposerProcessRunner
{
    public static function requirePackage($packageName, Process $process)
    {
        $process->start(app(LoopInterface::class));

        $process->stdout->on('data', function ($chunk) use($packageName) {
//            echo "✅ -> Data", PHP_EOL;
//            echo $chunk;
            Session::send(json_encode(["event" => "install.progress-{$packageName}", "data" => $chunk]));
        });

        $process->stderr->on('data', function ($chunk) use ($packageName) {
//            echo "❌ -> Error", PHP_EOL;
//            echo $chunk;
            if ($chunk && $chunk !== "" && $chunk !== " ")
                Session::send(json_encode(["event" => "install.progress-{$packageName}", "data" => $chunk]));
        });

        $process->stdout->on('error', function (Exception $e) {
//            echo "❌ -> Error", PHP_EOL;
//            echo 'Error: ' . $e->getMessage();
        });

        $process->on('exit', function($exitCode, $termSignal) use ($packageName) {
            if ($exitCode === 0) {
                Session::send(json_encode(["event" => "package.installed-{$packageName}", "data" => ["name" => $packageName, "installing" => false, "installed" => true]]));
            } else {
                Session::send(json_encode(["event" => "package.installation.failed-{$packageName}", "data" => ["name" => $packageName, "installing" => false, "installed" => false]]));
            }
        });
    }

    public static function run(Process $process)
    {
        // TODO: Implement run() method.
    }
}
