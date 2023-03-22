<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentUploadedMail;
use App\Events\DocumentUploaded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendDocumentUploadedMail
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
     * @param  \App\Events\DocumentUploaded  $event
     * @return void
     */
    public function handle(DocumentUploaded $event)
    {
        $email = $event->user->email;
        $mailData = [
            'name' => $event->user->name,
            'document_name' => $event->submission->document_name,
            'employee_name' => $event->employee->name,
            'status' => $event->submission->status,
        ];

        Mail::to($email)->send(
            new DocumentUploadedMail($mailData)
        );
    }
}
