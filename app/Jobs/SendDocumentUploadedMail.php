<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentUploadedMail;
use Illuminate\Bus\Queueable;
use App\Models\User;
use App\Models\Submission;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDocumentUploadedMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected User $user;
    protected User $employee;
    protected Submission $submission;

    public function __construct(User $user, User $employee, Submission $submission)
    {
        $this->user=$user;
        $this->employee=$employee;
        $this->submission=$submission;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = $this->user->email;
        $mailData = [
            'name' => $this->user->name,
            'document_name' => $this->submission->document_name,
            'employee_name' => $this->employee->name,
            'status' => $this->submission->status,
        ];

        Mail::to($email)->send(
            new DocumentUploadedMail($mailData)
        );
    }
}
