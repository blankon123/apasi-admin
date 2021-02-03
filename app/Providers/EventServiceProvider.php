<?php

namespace App\Providers;

use App\Events\PublikasiAdded;
use App\Events\PublikasiDeleted;
use App\Events\PublikasiDraftCommited;
use App\Events\PublikasiEdited;
use App\Events\PublikasiImported;
use App\Events\PublikasiRevisiCommited;
use App\Events\PublikasiSPRPCommited;
use App\Events\PublikasiSRCommited;
use App\Events\PublikasiUploaded;
use App\Listeners\PublikasiAddedListener;
use App\Listeners\PublikasiDeletedListener;
use App\Listeners\PublikasiDraftCommitedListener;
use App\Listeners\PublikasiEditedListener;
use App\Listeners\PublikasiImportedListener;
use App\Listeners\PublikasiRevisiCommitedListener;
use App\Listeners\PublikasiSPRPCommitedListener;
use App\Listeners\PublikasiSRCommitedListener;
use App\Listeners\PublikasiUploadedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        PublikasiAdded::class => [
            PublikasiAddedListener::class,
        ],
        PublikasiDeleted::class => [
            PublikasiDeletedListener::class,
        ],
        PublikasiDraftCommited::class => [
            PublikasiDraftCommitedListener::class,
        ],
        PublikasiEdited::class => [
            PublikasiEditedListener::class,
        ],
        PublikasiImported::class => [
            PublikasiImportedListener::class,
        ],
        PublikasiRevisiCommited::class => [
            PublikasiRevisiCommitedListener::class,
        ],
        PublikasiSPRPCommited::class => [
            PublikasiSPRPCommitedListener::class,
        ],
        PublikasiSRCommited::class => [
            PublikasiSRCommitedListener::class,
        ],
        PublikasiUploaded::class => [
            PublikasiUploadedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}