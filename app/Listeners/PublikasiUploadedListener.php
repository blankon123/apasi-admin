<?php

namespace App\Listeners;

use App\Events\PublikasiUploaded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PublikasiUploadedListener
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
     * @param  PublikasiUploaded  $event
     * @return void
     */
    public function handle(PublikasiUploaded $event)
    {
        //
    }
}
