<?php

namespace App\Providers;

use App\Events\PekerjaanDeleted;
use App\Events\PekerjaanDone;
use App\Events\PekerjaanProgress;
use App\Events\PublikasiAdded;
use App\Events\PublikasiDeleted;
use App\Events\PublikasiDesainRevised;
use App\Events\PublikasiDraftCommited;
use App\Events\PublikasiDraftRevised;
use App\Events\PublikasiEdited;
use App\Events\PublikasiErataRevised;
use App\Events\PublikasiImported;
use App\Events\PublikasiRevisiDone;
use App\Events\PublikasiRilisRevised;
use App\Events\PublikasiSPRPCommited;
use App\Events\PublikasiUploaded;
use App\Events\TabelDinamisAddedDone;
use App\Events\TabelDinamisDataEdited;
use App\Events\TabelDinamisDataEditedDone;
use App\Events\TabelDinamisDeleted;
use App\Events\TabelDinamisEditedDone;
use App\Events\TabelDinamisRequestAdded;
use App\Events\TabelDinamisRequestDeleted;
use App\Events\TabelDinamisRequestEdited;
use App\Listeners\PekerjaanDeletedListener;
use App\Listeners\PekerjaanDoneListener;
use App\Listeners\PekerjaanProgressListener;
use App\Listeners\PublikasiAddedListener;
use App\Listeners\PublikasiDeletedListener;
use App\Listeners\PublikasiDesainRevisedListener;
use App\Listeners\PublikasiDraftCommitedListener;
use App\Listeners\PublikasiDraftRevisedListener;
use App\Listeners\PublikasiEditedListener;
use App\Listeners\PublikasiErataRevisedListener;
use App\Listeners\PublikasiImportedListener;
use App\Listeners\PublikasiRevisiDoneListener;
use App\Listeners\PublikasiRilisRevisedListener;
use App\Listeners\PublikasiSPRPCommitedListener;
use App\Listeners\PublikasiUploadedListener;
use App\Listeners\TabelDinamisAddedDoneListener;
use App\Listeners\TabelDinamisDataEditedDoneListener;
use App\Listeners\TabelDinamisDataEditedListener;
use App\Listeners\TabelDinamisDeletedListener;
use App\Listeners\TabelDinamisEditedDoneListener;
use App\Listeners\TabelDinamisRequestAddedListener;
use App\Listeners\TabelDinamisRequestDeletedListener;
use App\Listeners\TabelDinamisRequestEditedListener;
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
        PublikasiDraftRevised::class => [
            PublikasiDraftRevisedListener::class,
        ],
        PublikasiDesainRevised::class => [
            PublikasiDesainRevisedListener::class,
        ],
        PublikasiRilisRevised::class => [
            PublikasiRilisRevisedListener::class,
        ],
        PublikasiErataRevised::class => [
            PublikasiErataRevisedListener::class,
        ],
        PublikasiSPRPCommited::class => [
            PublikasiSPRPCommitedListener::class,
        ],
        PublikasiRevisiDone::class => [
            PublikasiRevisiDoneListener::class,
        ],
        PublikasiUploaded::class => [
            PublikasiUploadedListener::class,
        ],

        PekerjaanProgress::class => [
            PekerjaanProgressListener::class,
        ],
        PekerjaanDone::class => [
            PekerjaanDoneListener::class,
        ],
        PekerjaanDeleted::class => [
            PekerjaanDeletedListener::class,
        ],

        TabelDinamisRequestAdded::class => [
            TabelDinamisRequestAddedListener::class,
        ],
        TabelDinamisRequestEdited::class => [
            TabelDinamisRequestEditedListener::class,
        ],
        TabelDinamisRequestDeleted::class => [
            TabelDinamisRequestDeletedListener::class,
        ],
        TabelDinamisDataEdited::class => [
            TabelDinamisDataEditedListener::class,
        ],
        TabelDinamisDataEditedDone::class => [
            TabelDinamisDataEditedDoneListener::class,
        ],
        TabelDinamisEditedDone::class => [
            TabelDinamisEditedDoneListener::class,
        ],
        TabelDinamisAddedDone::class => [
            TabelDinamisAddedDoneListener::class,
        ],
        TabelDinamisDeleted::class => [
            TabelDinamisDeletedListener::class,
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