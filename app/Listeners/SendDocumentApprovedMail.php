<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentApprovedMail;
use App\Events\DocumentApproved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendDocumentApprovedMail as DocumentApprovedJob;

class SendDocumentApprovedMail
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
     * @param  \App\Events\DocumentApproved  $event
     * @return void
     */
    public function handle(DocumentApproved $event)
    {
        $email=$event->user->email;
        $mailData = [
            'name' => $event->user->name,
            'document_name' => $event->submission->document_name,
        ];

        DocumentApprovedJob::dispatch($email, $mailData, $event->submission);
    }
}
