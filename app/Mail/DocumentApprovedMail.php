<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $mailData;
    private $submission;

    public function __construct($mailData, $submission)
    {
        $this->mailData=$mailData;
        $this->submission=$submission;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('3SY Portal - Documento approvato')
        ->view('emails.DocumentApproved');
        /*->attachFromStorage('/'.$this->submission->document_path);*/
    }
}
