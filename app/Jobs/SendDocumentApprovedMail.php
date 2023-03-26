<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\DocumentApprovedMail;
use App\Models\User;
use App\Models\Submission;
use Illuminate\Support\Facades\Mail;

class SendDocumentApprovedMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $email;
    protected $mailData;
    protected $submission;

    public function __construct($email,$mailData,$submission)
    {
        $this->email=$email;
        $this->mailData=$mailData;
        $this->submission=$submission;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(
            new DocumentApprovedMail($this->mailData,$this->submission)
        );
    }
}
