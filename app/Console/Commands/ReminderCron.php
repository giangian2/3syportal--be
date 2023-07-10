<?php

namespace App\Console\Commands;

use DB;
use Mail;
use DateTime;
use Carbon\Carbon;
use App\Models\Submission;
use App\Models\User;
use App\Mail\Reminder7gMail;
use App\Mail\Reminder3gMail;
use Illuminate\Console\Command;

class ReminderCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('--------------------------------------');
        $this->info('RUNNING COMMAND TO SEND REMINDER MAILS');
        $submissions=Submission::whereIn('status', array('rejected','in approval','document required','signature required'))->get();

        foreach($submissions as $submission){

            $date=$submission->created_at;
            $parsed_starting_date=new DateTime($date);
            $now=Carbon::now();
            $parsed_final_date=new DateTime($now);
            $interval=$parsed_starting_date->diff($parsed_final_date);
            $days=$interval->format('%a');
            $id=$submission->to_user;

            $this->info('$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$');
            $this->info('Submission '.$submission->id);
            $this->info('from_user '.$submission->from_user);
            $this->info('to_user '.$submission->to_user);
            $this->info('created_at '.$submission->created_at);
            $this->info('$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$');

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

