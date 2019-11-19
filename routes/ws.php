<?php

/*
|--------------------------------------------------------------------------
| Websocket Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your Websocket application.
|
*/

use Xeviant\Async\Foundation\WebSocket\ControllerHandler;

Ws::ws("/", new ControllerHandler);
