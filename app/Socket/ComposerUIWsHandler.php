<?php

namespace App\Socket;

use App\AppClient;
use Exception;
use Xeviant\Async\Foundation\Logging\LogPrinter;
use Xeviant\Async\Foundation\WebSocket\WebSocketMessage;
use Xeviant\Async\Foundation\WebSocket\WebSocketRequest;
use Xeviant\Async\Foundation\WebSocketHandler;
use Closure;

class ComposerUIWsHandler extends WebSocketHandler
{
    private $clients;

    public function onOpen(WebSocketRequest $request)
    {
        $this->clients[$request->getClientId()] = new AppClient($request->getConnection());
    }


    public function onMessage(WebSocketMessage $message)
    {
        $messageData = json_decode($message->getMessage());

        if (strpos($messageData->action, '@') === false) {
            $callable = [
                app()->make(str_replace('.', '\\', $messageData->action)),
                "handle",
            ];
        } else {
            [$class, $method] = explode('@', $messageData->action);

            $callable = [
                app()->make(str_replace('.', '\\', $class)),
                $method,
            ];
        }

        try {
            app()->call(
                Closure::fromCallable($callable),
                ['client' => $this->clients[$message->getClientId()], 'data' => $messageData->data ?? null]
            );
        } catch (Exception $exception) {
            LogPrinter::error($exception);
        }
    }
}
