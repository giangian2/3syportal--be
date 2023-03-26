<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use App\Mail\SubmissionCreatedMail;
use App\Events\SubmissionCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\SendSubmissionCreatedMail as SubmissionCreatedJob;

class SendSubmissionCreatedMail
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
     * @param  \App\Events\SubmissionCreated  $event
     * @return void
     */
    public function handle(SubmissionCreated $event)
    {
        $email=$event->user->email;
        $mailData = [
            'name' => $event->user->name,
	        'document_name' => $event->submission->document_name,
	        'status' => $event->submission->status,
        ];

        SubmissionCreatedJob::dispatch($email, $mailData, $event->submission);
    }
}
