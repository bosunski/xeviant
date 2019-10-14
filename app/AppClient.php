<?php

namespace App;

use Xeviant\Async\Contracts\WebSocket\WebSocketConnection;
use Xeviant\Async\Contracts\WebSocketComponent;
use Xeviant\Async\Foundation\WebSocket\Session;

class AppClient
{
    protected $command;

    /**
     * @var WebSocketComponent
     */
    private $connection;

    public function __construct(WebSocketConnection $conn = null)
    {
        $this->connection = $conn;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->connection->id();
    }

    public function send(string $data): void
    {
        $this->connection->send($this->getClientId());
    }

    public function sendJSON(string $action, array $data): void
    {
        Session::send(
            json_encode([
                'action' => str_replace('\\', '.', $action),
                'data' => (array) $data
            ])
        );
    }

    public function sendEvent(string $event, array $data): void
    {
        Session::send(
            json_encode([
                'event' => str_replace('\\', '.', $event),
                'data' => (array) $data
            ])
        );
    }
}
