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
    protected User $user;
    protected Submission $submission;

    public function __construct(User $user, Submission $submission)
    {
        $this->user=$user;
        $this->submission=$submission;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $email=$this->user->email;
        $mailData = [
            'name' => $this->user->name,
	        'document_name' => $this->submission->document_name,
	        'status' => $this->submission->status,
        ];

        Mail::to($email)->send(
            new SubmissionCreatedMail($mailData, $this->submission->status)
        );
    }
}
