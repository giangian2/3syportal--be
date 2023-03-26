<?php

namespace App\Listeners;

use Mail;
use App\Events\PasswordReset;
use App\Mail\PasswordResetMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPasswordResetMail
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
     * @param  \App\Events\PasswordReset  $event
     * @return void
     */
    public function handle(PasswordReset $event)
    {
        $email=$event->user->email;
        $url=env('APP_URL').env('APP_RESET_PASSWORD_URL')."?id=".$event->user->id."&email=".$email."&token=".$event->token_confirmation."&firstAccess=false";
        $mailData = [
            'name' => $event->user->name,
            'url' => $url,
        ];
        Mail::to($email)->send(
            new PasswordResetMail($mailData)
        );
    }
}
