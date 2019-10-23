<?php

namespace App\Events;

use App\Domain\Notebook\AbstractNotebook;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotebookApplicationStarted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var AbstractNotebook
     */
    public $notebook;

    /**
     * Create a new event instance.
     *
     * @param AbstractNotebook $notebook
     */
    public function __construct(AbstractNotebook $notebook)
    {
        $this->notebook = $notebook;
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
