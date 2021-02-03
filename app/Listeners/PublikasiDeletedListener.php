<?php

namespace App\Listeners;

use App\Events\PublikasiDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PublikasiDeletedListener
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
     * @param  PublikasiDeleted  $event
     * @return void
     */
    public function handle(PublikasiDeleted $event)
    {
        //
    }
}
