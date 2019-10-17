<?php

namespace App;

use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;
use Xeviant\Async\Foundation\WebSocket\Session;

class ComposerProcessRunner
{
    public static function requirePackage($identifier, Process $process)
    {
        $process->start(app(LoopInterface::class));

        $process->stdout->on('data', function ($chunk) use($identifier) {
//            echo "✅ -> Data", PHP_EOL;
//            echo $chunk;
            Session::send(json_encode(["event" => "install.progress-{$identifier}", "data" => $chunk]));
        });

        $process->stderr->on('data', function ($chunk) use ($identifier) {
//            echo "❌ -> Error", PHP_EOL;
//            echo $chunk;
            if ($chunk && $chunk !== "" && $chunk !== " ")
                Session::send(json_encode(["event" => "install.progress-{$identifier}", "data" => $chunk]));
        });

        $process->stdout->on('error', function (Exception $e) {
//            echo "❌ -> Error", PHP_EOL;
//            echo 'Error: ' . $e->getMessage();
        });

        $process->on('exit', function($exitCode, $termSignal) use ($identifier) {
            if ($exitCode === 0) {
                Session::send(json_encode(["event" => "package.installed-{$identifier}", "data" => ["name" => $identifier, "installing" => false, "installed" => true]]));
            } else {
                Session::send(json_encode(["event" => "package.installation.failed-{$identifier}", "data" => ["name" => $identifier, "installing" => false, "installed" => false]]));
            }
        });
    }

    public static function removePackage($identifier, Process $process)
    {
        $process->start(app(LoopInterface::class));
        $process->stdout->on('data', function ($chunk) use($identifier) {
            Session::send(json_encode(["event" => "remove.progress-{$identifier}", "data" => $chunk]));
        });

        $process->stderr->on('data', function ($chunk) use ($identifier) {
//            echo "❌ -> Error", PHP_EOL;
//            echo $chunk;
            if ($chunk && $chunk !== "" && $chunk !== " ")
                Session::send(json_encode(["event" => "remove.progress-{$identifier}", "data" => $chunk]));
        });

        $process->stdout->on('error', function (Exception $e) {
//            echo "❌ -> Error", PHP_EOL;
//            echo 'Error: ' . $e->getMessage();
        });

        $process->on('exit', function($exitCode, $termSignal) use ($identifier) {
            if ($exitCode === 0) {
                Session::send(json_encode(["event" => "package.removed-{$identifier}", "data" => ["name" => $identifier, "installing" => false, "installed" => true]]));
            } else {
                Session::send(json_encode(["event" => "package.removal.failed-{$identifier}", "data" => ["name" => $identifier, "installing" => false, "installed" => false]]));
            }
        });
    }

    public static function run(Process $process)
    {
        // TODO: Implement run() method.
    }
}
