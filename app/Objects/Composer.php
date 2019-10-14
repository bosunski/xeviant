<?php

namespace App\Objects;

use function Amp\call;
use function Amp\File\get;
use Amp\File\Handle;
use function Amp\File\isdir;
use function Amp\File\isfile;
use function Amp\File\open;
use Amp\Promise;
use Amp\ReactAdapter\ReactAdapter;
use React\ChildProcess\Process;
use Xeviant\Async\Foundation\Application\AsyncAsyncApplication;
use Xeviant\Async\Foundation\Server\WebSocketServer;

class Composer
{
    /**
     * @var string
     */
    private $dir;
    /**
     * @var string
     */
    private $clientId;

    /**
     * @var WebSocketServer
     */
    private $socket;

    public function __construct(string $dir, string $clientId)
    {
        $this->dir = $dir;
        $this->clientId = $clientId;
        $this->socket = AsyncAsyncApplication::getInstance()->make("socket.server");
    }

    /**
     * @return Promise|bool
     */
    public function init(): Promise
    {
        $path = $this->dir . "/composer.json";

        return call(function($path) {
            if (! yield isdir($this->dir)) {
                yield \Amp\File\mkdir($this->dir, 0777, true);
            }

            if (!yield isfile($path)) {
                $handle = yield $this->createFileHandler($path, "w");

                if (!$handle) {
                    return false;
                }

                $stubContent = yield get("stubs/composer.json");

                $newCodeContent = str_replace(
                    "@@clientId",
                    $this->clientId,
                    $stubContent
                );

                /**
                 * @var $handle Handle;
                 */
                yield $handle->write($newCodeContent);

                yield $handle->close();
            }

            return true;
        }, $path);
    }

    protected function createFileHandler(string $path, $mode = "w")
    {
        return call(function($path, $mode) {
            try {
                return $handle = yield open($path, $mode);
            } catch (\Exception $exception) {
                return false;
            }
        }, $path, $mode);
    }

    public function requirePackage(string $packageName)
    {
        $command = sprintf("$(which composer) require %s --working-dir=%s", $packageName, $this->dir);

        $process = new Process($command);

        $process->start(ReactAdapter::get());

        $process->stdout->on('data', function ($chunk) {
//            echo "✅ -> Data", PHP_EOL;
//            echo $chunk;
            $this->socket->send(json_encode(["event" => "install.progress", "data" => $chunk]), $this->clientId);
        });

        $process->stderr->on('data', function ($chunk) {
//            echo "❌ -> Error", PHP_EOL;
//            echo $chunk;
            if ($chunk && $chunk !== "" && $chunk !== " ")
            $this->socket->send(json_encode(["event" => "install.progress", "data" => $chunk]), $this->clientId);
        });

        $process->stdout->on('error', function (Exception $e) {
//            echo "❌ -> Error", PHP_EOL;
//            echo 'Error: ' . $e->getMessage();
        });

        $process->on('exit', function($exitCode, $termSignal) use ($packageName) {
            if ($exitCode === 0) {
                $this->socket->send(json_encode(["event" => "package.installed", "data" => ["name" => $packageName, "installing" => false, "installed" => true]]), $this->clientId);
            } else {
                $this->socket->send(json_encode(["event" => "package.installation.failed", "data" => ["name" => $packageName, "installing" => false, "installed" => false]]), $this->clientId);
            }
        });
    }

    public function removePackage(string $packageName)
    {
        $command = sprintf("composer remove %s --working-dir=%s", $packageName, $this->dir);

        $process = new Process($command);

        $process->start(ReactAdapter::get());

        $process->stdout->on('data', function ($chunk) {
            echo "✅ -> Data", PHP_EOL;
            echo $chunk;
            $this->socket->send(json_encode(["event" => "remove.progress", "data" => $chunk]), $this->clientId);
        });

        $process->stderr->on('data', function ($chunk) {
            echo "❌ -> Error", PHP_EOL;
            echo $chunk;
            $this->socket->send(json_encode(["event" => "remove.progress", "data" => $chunk]), $this->clientId);
        });

        $process->stdout->on('error', function (Exception $e) {
            echo "❌ -> Error", PHP_EOL;
            echo 'Error: ' . $e->getMessage();
        });

        $process->on('exit', function($exitCode, $termSignal) use ($packageName) {
            if ($exitCode === 0) {
                $this->socket->send(json_encode(["event" => "package.removed", "data" => ["name" => $packageName, "removing" => false, "removed" => true]]), $this->clientId);
            } else {
                $this->socket->send(json_encode(["event" => "package.removal.failed", "data" => ["name" => $packageName, "removing" => false, "removed" => false]]), $this->clientId);
            }
        });
    }
}
