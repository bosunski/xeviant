<?php

use Xeviant\Async\Foundation\Routing\AsyncRouter;
use Xeviant\Async\Foundation\WebSocket\ControllerHandler;

return function (AsyncRouter $router) {
    $router->ws("/", new ControllerHandler);
};
