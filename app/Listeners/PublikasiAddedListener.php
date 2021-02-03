<?php

namespace App\Listeners;

use App\Events\PublikasiAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PublikasiAddedListener
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
     * @param  PublikasiAdded  $event
     * @return void
     */
    public function handle(PublikasiAdded $event)
    {
        //
    }
}
