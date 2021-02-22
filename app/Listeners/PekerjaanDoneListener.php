<?php

namespace App\Listeners;

use App\Events\PekerjaanDone;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PekerjaanDoneListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PekerjaanDone  $event
     * @return void
     */
    public function handle(PekerjaanDone $event)
    {
        //
    }
}
