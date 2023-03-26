<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubmissionCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $mailData;
    public $status;

    public function __construct($mailData, $status)
    {
	    $this->mailData=$mailData;
	    $this->status=$status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	    $subject="3SY Portal - Richiesta documento";
	    
	if($this->status=='document required'){
		$subject='3SY Portal - Richiesta docuemtno';
	}else{
		$subject='3SY Portal - Richiesta firma documento';
	}
        return $this->subject($subject)
        ->view('emails.SubmissionCreated');
    }
}
