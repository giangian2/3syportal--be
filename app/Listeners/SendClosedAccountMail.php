<?php

namespace App\Listeners;

use Mail;
use App\Mail\UserDeletedMail;
use App\Events\DeletedUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendClosedAccountMail
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
     * @param  \App\Events\DeletedUser  $event
     * @return void
     */
    public function handle(DeletedUser $event)
    {
        $email=$event->user->email;
        $mailData = [
            'name' => $event->user->name,
        ];
        
        Mail::to($email)->send(
            new UserDeletedMail($mailData)
        );
    }
}
