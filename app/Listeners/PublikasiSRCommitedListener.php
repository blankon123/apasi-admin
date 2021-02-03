<?php

namespace App\Listeners;

use App\Events\PublikasiSRCommited;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PublikasiSRCommitedListener
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
     * @param  PublikasiSRCommited  $event
     * @return void
     */
    public function handle(PublikasiSRCommited $event)
    {
        //
    }
}
