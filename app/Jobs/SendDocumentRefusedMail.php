<?php

namespace App\Jobs;

use App\Mail\DocumentRefusedMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDocumentRefusedMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected  $email;
    protected  $mailData;

    public function __construct($email,$mailData)
    {
        $this->email=$email;
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
            new DocumentRefusedMail($this->mailData)
        );
    }
}
