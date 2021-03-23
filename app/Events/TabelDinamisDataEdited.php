<?php

namespace App\Events;

use App\Models\TabelDinamis;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TabelDinamisDataEdited
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TabelDinamis $tabel_old, TabelDinamis $tabel, User $user, String $msg)
    {
        $this->tabel_old = $tabel_old;
        $this->tabel = $tabel;
        $this->user = $user;
        $this->perubahan = $msg;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}