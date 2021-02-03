<?php

namespace App\Listeners;

use App\Events\PublikasiEdited;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PublikasiEditedListener
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
     * @param  PublikasiEdited  $event
     * @return void
     */
    public function handle(PublikasiEdited $event)
    {
        //
    }
}
