<?php

namespace App\Actions;

use Xeviant\Async\Foundation\WebSocket\Session;
use Xeviant\Async\Foundation\WebSocket\WebSocketRequest;

class ComposerController
{
    public function initialize(string $directory, WebSocketRequest $request)
    {
        Session::send($request->directory);
    }
}
