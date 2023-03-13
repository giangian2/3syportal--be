<?php

namespace App\Providers;

use App\Events\RegisteredNewUser;
use App\Events\SubmissionCreated;
use App\Events\PasswordReset;
use App\Events\DeletedUser;
use App\Events\DocumentApproved;
use App\Events\DocumentRefused;
use App\Events\DocumentUploaded;
use App\Listeners\SendDocumentApprovedMail;
use App\Listeners\SendDocumentRefusedMail;
use App\Listeners\SendDocumentUploadedMail;
use App\Listeners\SendRegisteredMail;
use App\Listeners\SendClosedAccountMail;
use App\Listeners\SendPasswordResetMail;
use App\Listeners\SendSubmissionCreatedMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        RegisteredNewUser::class => [
            SendRegisteredMail::class,
        ],
        DeletedUser::class => [
            SendClosedAccountMail::class,
        ],
        PasswordReset::class => [
            SendPasswordResetMail::class,
        ],
        SubmissionCreated::class => [
            SendSubmissionCreatedMail::class,
        ],
        DocumentApproved::class => [
            SendDocumentApprovedMail::class,
        ],
        DocumentRefused::class => [
            SendDocumentRefusedMail::class,
        ],
        DocumentUploaded::class => [
            SendDocumentUploadedMail::class
        ]
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

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
