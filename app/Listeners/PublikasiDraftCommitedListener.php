<?php

namespace App\Listeners;

use App\Events\PublikasiDraftCommited;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PublikasiDraftCommitedListener
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
     * @param  PublikasiDraftCommited  $event
     * @return void
     */
    public function handle(PublikasiDraftCommited $event)
    {
        //
    }
}
