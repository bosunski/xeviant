<?php

namespace Xeviant\ComposerUI\Http\Responders;

use App\AppClient;
use Xeviant\Async\Foundation\Server\WebSocketServer;

class DirectoryResponder
{
    // DirectoryResponder.listDirectoryContents
    public function listDirectoryContents(WebSocketServer $server, AppClient $codeClient, $pkgName): void
    {
        $server->send("Boom", $codeClient->getClientId());
    }
    public function changeDirectory(WebSocketServer $server, AppClient $codeClient, $pkgName): void
    {
    }
}
