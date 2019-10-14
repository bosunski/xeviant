<?php

namespace App\Socket;

use Amp\Http\Server\Request;
use Amp\Http\Server\Response;
use Amp\Http\Server\Websocket\Message;
use Amp\Http\Server\Websocket\Websocket;
use function Amp\call;
use ArrayAccess;
use Amp\Redis\Client;
use App\AppClient;
use Xeviant\Async\Foundation\Application\AsyncAsyncApplication;
use Closure;

class WebSocketServer extends Websocket
{
    const NAMESPACE = "App\\Http\\Responders\\";
    /**
     * @var LoopInterface
     */
    private $loop;

    /**
     * @var AppClient[]
     */
    private $clients = [];

    public function __construct($loop = null)
    {
        $this->loop = $loop;
        parent::__construct();
    }

    public function onHandshake(Request $request, Response $response)
    {
        return $response;
    }

    public function onConnect(Client $client, Request $request, Response $response)
    {
    }

    public function onData(int $clientId, Message $message)
    {
        $message = json_decode(yield $message->buffer());

        if (strpos($message->action, '@') === false) {
            $callable = [
                AsyncAsyncApplication::getInstance()->make(str_replace('.', '\\', $message->action)),
                "handle",
            ];
        } else {
            [$class, $method] = explode('@', $message->action);

            $callable = [
                AsyncAsyncApplication::getInstance()->make(str_replace('.', '\\', $class)),
                $method,
            ];
        }

        try {
            yield call(Closure::fromCallable($callable), $this, $this->clients[$clientId], $message->data ?? null);
        } catch (\Exception $exception) {
            echo "Error: " . $exception->getMessage();
        }
    }

    public function sendToClient($data, $clientId)
    {
        if ($data instanceof ArrayAccess) {
            $data = json_encode($data);
        }

        call(function ($data, $clientId) {
            yield $this->send($data, $clientId);
        }, $data, $clientId);
    }

    public function onClose(int $clientId, int $code, string $reason)
    {
        try {
            $this->close($clientId);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }


    public function onOpen(int $clientId, Request $request)
    {
        $this->clients[$clientId] = new AppClient($clientId, $this);
    }
}
