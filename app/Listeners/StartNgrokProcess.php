<?php

namespace App\Listeners;

use App\Events\NotebookApplicationStarted;
use App\Events\NotebookTunnellingComplete;
use App\Scripts\Ngrok\NgrokHttp;
use Exception;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;
use Xeviant\Async\Foundation\WebSocket\Session;

class StartNgrokProcess
{
    protected $notebookId;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param NotebookApplicationStarted $event
     * @return void
     */
    public function handle(NotebookApplicationStarted $event)
    {
        Session::sendEvent("Notebook.Provision.Status". $event->notebook->getNotebookId(), ["step" => 2, "message" => "Creating URL"]);
        $this->startNotebook($event->notebook->getNotebookId(), $event->notebook->getPort());
    }

    protected function startNotebook(string $notebookId, int $port): void
    {
        $ngrokProcess = new Process((string) new NgrokHttp($port));

        $ngrokProcess->start(app(LoopInterface::class));

        $ngrokProcess->stderr->on('data', function($chunk) {
            echo $chunk;
        });

        $ngrokProcess->stdin->on('data', function ($c) {
            echo $c;
        });

        $ngrokProcess->stdout->on('data', function ($chunk) use ($notebookId, $port) {
            echo $chunk;

            if (Str::contains($chunk, "url=https://")) {

                event(new NotebookTunnellingComplete($notebookId, $this->getNgrokUrlFromOutputLog($chunk)));

                $url = $this->getNgrokUrlFromOutputLog($chunk);

                // We Send the URL Back Over Websocket
                Session::sendEvent("Notebook.URL.". $this->notebookId, [
                    "public_url" => $url,
                    "message" => "URL Available for Next 7 Hours!"
                ]);
            }
        });
    }

    protected function getNgrokUrlFromOutputLog(string $log): string
    {
        preg_match_all('/url=(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[-A-Z0-9+&@#\/%=~_|$?!:,.])*(?:\([-A-Z0-9+&@#\/%=~_|$?!:,.]*\)|[A-Z0-9+&@#\/%=~_|$])/i', $log, $matches);
        return str_replace('url=', "", $matches[0][0] ?? "");
    }
}
