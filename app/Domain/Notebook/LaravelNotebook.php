<?php


namespace App\Domain\Notebook;

use App\Scripts\Laravel\ArtisanServe;
use App\Scripts\Laravel\NewLaravelScript;
use Illuminate\Support\Str;
use React\ChildProcess\Process;
use Xeviant\Async\Foundation\Server\Port;
use Xeviant\Async\Foundation\WebSocket\Session;

class LaravelNotebook extends AbstractNotebook
{
    public function createNotebook(callable $onCreated = null)
    {
        $process = new Process((string) NewLaravelScript::make($this->path));

        Session::sendEvent("Notebook.Provision.Status.". $this->notebookId, ["step" => 1, "message" => "Creating Application!"]);

        $process->start($this->loop);

        $process->stdout->on('data', [$this, "handleOutput"]);

        $process->stderr->on('data', [$this, "handleOutput"]);

        $process->on('exit', function($exitCode) use ($onCreated) {
            $this->handleCreationExit($exitCode, $onCreated);
        });
    }

    public function handleOutput(string $outputChunk)
    {
        echo $outputChunk;
    }

    public function handleCreationExit(int $exitCode, callable $onCreated)
    {
        if ($exitCode === 0) {
            // Laravel New Script ran Successfully, We'll Notify the frontend about it.
            if ($onCreated) {
                $onCreated($this);
            }
        }
    }

    public function startNotebook(callable $onStart = null)
    {
        event('notebook.starting', $this->notebookId);

        Session::sendEvent("Notebook.Provision.Status.". $this->notebookId, [
            "step" => 2,
            "message" => "Starting Application"
        ]);

        // We Start an Artisan Se
        $serveProcess = new Process(
            (string) new ArtisanServe($this->path, $port = Port::randomPort())
        );

        $serveProcess->start($this->loop);

        $serveProcess->stdout->on('data', function ($chunk) use ($onStart, $port) {
            echo $chunk;
            if (Str::contains($chunk, "Laravel development server started")) {
                // Development Server Started, The next step is URL creation for the APP
                $this->setPort($port);

                if ($onStart) {
                    $onStart($this);
                }
            }
        });
    }
}
