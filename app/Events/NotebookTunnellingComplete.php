<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotebookTunnellingComplete
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var string
     */
    public $tunnellingUrl;
    /**
     * @var string
     */
    public $notebookId;

    /**
     * Create a new event instance.
     *
     * @param string $notebookId
     * @param string $tunnellingUrl
     */
    public function __construct(string $notebookId, string $tunnellingUrl)
    {
        $this->tunnellingUrl = $tunnellingUrl;
        $this->notebookId = $notebookId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
