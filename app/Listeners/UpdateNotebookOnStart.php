<?php

namespace App\Listeners;

use App\Events\NotebookTunnellingComplete;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Psr\Http\Message\ResponseInterface;
use Xeviant\Async\Foundation\WebSocket\Session;

class UpdateNotebookOnStart
{
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
     * @param NotebookTunnellingComplete $event
     * @return void
     */
    public function handle(NotebookTunnellingComplete $event)
    {
        fetch(env('LARAVEL_APP_URL') . '/notebook/provisioned/' . $event->notebookId . "?public_url=$event->tunnellingUrl")
            ->then(function(ResponseInterface $response) use ($event) {
                Session::sendEvent("Notebook.Provisioned.". $event->notebookId, []);
            }, function(Exception $e) {
                var_dump($e->getMessage());
            });
    }
}
