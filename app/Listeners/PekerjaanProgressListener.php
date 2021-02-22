<?php

namespace App\Listeners;

use App\Events\PekerjaanProgress;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PekerjaanProgressListener
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
     * @param  PekerjaanProgress  $event
     * @return void
     */
    public function handle(PekerjaanProgress $event)
    {
        //
    }
}
