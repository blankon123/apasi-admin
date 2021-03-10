<?php

namespace App\Listeners;

use App\Events\TabelDinamisDataEdited;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TabelDinamisDataEditedListener
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
     * @param  TabelDinamisDataEdited  $event
     * @return void
     */
    public function handle(TabelDinamisDataEdited $event)
    {
        //
    }
}
