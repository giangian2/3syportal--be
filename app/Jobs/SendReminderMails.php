<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Submission;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\Reminder3gMail;
use App\Mail\Reminder7gMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendReminderMails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $submissions=Submission::whereIn('status', array('rejected','in approval','document required','signature required'))->get();

        foreach($submissions as $submission){

            $date=$submission->created_at;
            $parsed_starting_date=new DateTime($date);
            $now=Carbon::now();
            $parsed_final_date=new DateTime($now);
            $interval=$parsed_starting_date->diff($parsed_final_date);
            $days=$interval->format('%a');
            $id=$submission->to_user;


            $receiver=User::find($id);
            $reminder_mail=DB::table('reminder_mails')
                            ->where('submission_id', $submission->id)
                            ->first();

            if(!$reminder_mail)continue;

	        if($days >= 3 && $days < 7 && $reminder_mail->reminder3g_sent_at==NULL)
	        {
                $mailData=[
                    'name' => $receiver->name,
                    'document_name' => $submission->document_name,
                ];

                Mail::to($receiver->email)->send(
                    new Reminder3gMail($mailData)
		        );

		        DB::table('reminder_mails')
                    ->where('submission_id', $submission->id)
                    ->update(['reminder3g_sent_at' => Carbon::now()]);

            }else if($days >= 7 && $reminder_mail->reminder7g_sent_at == NULL){
                $sender=User::find($submission->from_user);

                $mailData=[
                    'name' => $sender->name,
                    'employee_name' => $receiver->name,
                    'document_name' => $submission->document_name,
                ];
                Mail::to($sender->email)->send(
                    new Reminder7gMail($mailData)
		        );

		        DB::table('reminder_mails')
                    ->where('submission_id', $submission->id)
                    ->update(['reminder7g_sent_at' => Carbon::now()]);
            }
        }
        return 0;
    }
}
