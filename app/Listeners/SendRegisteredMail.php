<?php

namespace App\Listeners;

use Mail;
use App\Mail\FirstAccessMail;
use App\Events\RegisteredNewUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRegisteredMail
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
     * @param  \App\Events\RegisteredNewUser  $event
     * @return void
     */
    public function handle(RegisteredNewUser $event)
    {
        $email=$event->user->email;
        $url=env('APP_URL').env('APP_RESET_PASSWORD_URL')."?id=".$event->user->id."&email=".$email."&token=".$event->token_confirmation."&firstAccess=true";
        $mailData = [
            'name' => $event->user->name,
            'url' => $url,
        ];
        Mail::to($email)->send(
            new FirstAccessMail($mailData)
        );
    }
}
