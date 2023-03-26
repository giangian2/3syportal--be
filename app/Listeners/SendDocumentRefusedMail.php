<?php

namespace App\Listeners;

use Mail;
use App\Mail\DocumentRefusedMail;
use App\Events\DocumentRefused;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendDocumentRefusedMail as DocumentRefusedJob;

class SendDocumentRefusedMail
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
     * @param  \App\Events\DocumentRefused  $event
     * @return void
     */
    public function handle(DocumentRefused $event)
    {
        $email=$event->user->email;
        $mailData = [
            'name' => $event->user->name,
            'document_name' => $event->submission->document_name,
            'notes' => $event->submission->notes
        ];

        DocumentRefusedJob::dispatch($email,$mailData);
    }
}
