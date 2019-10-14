<?php


namespace App\Actions;


use App\AppClient;
use App\Socket\WebSocketServer;

class GetClientId
{
    public function handle(AppClient $client, $pkgName): void
    {
        $client->sendJSON(__CLASS__, ['id' => $client->getClientId()]);
    }
}
