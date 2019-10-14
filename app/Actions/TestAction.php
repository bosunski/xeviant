<?php

namespace App\Actions;

use Xeviant\Async\Foundation\WebSocket\Session;

class TestAction
{
    public function handle()
    {
        Session::send(
            json_encode([
                'event' => str_replace('\\', '.', __CLASS__),
                'data' => ["SessId" => Session::id()]
            ])
        );
    }

}
