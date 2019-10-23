<?php


namespace App\Scripts\Ngrok;


use App\Scripts\Script;

class NgrokHttp extends Script
{
    /**
     * @var int
     */
    private $port;

    public function __construct(int $port)
    {
        $this->port = $port;
    }

    public function script(): string
    {
        return "$(which ngrok) http --log=stdout $this->port";
    }
}
