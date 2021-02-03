<?php

namespace App\Listeners;

use App\Events\PublikasiSPRPCommited;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PublikasiSPRPCommitedListener
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
     * @param  PublikasiSPRPCommited  $event
     * @return void
     */
    public function handle(PublikasiSPRPCommited $event)
    {
        //
    }
}
