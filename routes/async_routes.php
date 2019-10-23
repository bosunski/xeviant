<?php

use App\Http\Responders\NotebookResponder;
use Psr\Http\Message\ResponseInterface;
use Xeviant\Async\Foundation\Routing\AsyncRouter;
use Xeviant\Async\Foundation\WebSocket\ControllerHandler;

return function (AsyncRouter $router) {
    $router->ws("/", new ControllerHandler);
    $router->get('/root', function() {
        return "Hello World";
    });

    $router->get('/', function() {
        $a = yield fetch("http://127.0.0.1:4040/api/tunnels");
        return (string) $a->getBody();
        var_dump(json_decode((string) $a->getBody(), true));
    });
};
