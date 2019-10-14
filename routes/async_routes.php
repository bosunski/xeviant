<?php

use App\Http\Responders\NotebookResponder;
use Xeviant\Async\Foundation\Routing\AsyncRouter;
use Xeviant\Async\Foundation\WebSocket\ControllerHandler;

return function (AsyncRouter $router) {
    $router->ws("/", new ControllerHandler);
    $router->get('/root', function() {
        return "Hello World";
    });
};
