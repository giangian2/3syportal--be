<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubmissionCreatedMail;

class SendSubmissionCreatedMail implements ShouldQueue
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

    public function __construct($email, $mailData, $submission)
    {
        $this->email=$email;
        $this->submission=$submission;
        $this->mailData=$mailData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Mail::to($this->email)->send(
            new SubmissionCreatedMail($this->mailData, $this->submission->status)
        );
    }
}
