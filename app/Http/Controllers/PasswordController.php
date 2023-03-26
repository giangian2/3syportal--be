<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Mail;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Password;

class PasswordController extends Controller
{
    /*This Method will send a mail to the nuser with the mail given in input,
    the mail contains a link to call the reset_password endpoint */
    public function send_reset_password_mail(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $user = User::where('email', $request->email)->first();

        if($user->deleted_at != NULL){
            return response([
                'message' => 'this user was deleted at '.$user->deleted_at,
            ], 404);
        }

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        event(new PasswordReset($user, $token));

        return response()->json([
            'message' => 'email was sent succesfully',
        ]);
    }


    public function reset_password(Request $request){

        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'token' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
                        ->where([
                            'email' => $request->email,
                            'token' => $request->token
                        ])
                        ->first();


        if(!$updatePassword){
            return response("Bad Token", 401);
        }

        $user = User::where('email', $request->email)
                      ->update(['password' => bcrypt($request->password)]);

        $user = User::where('email', $request->email)->first();

        if($user->deleted_at){
            return response([
                'message' => 'this user was deleted at '.$user->deleted_at,
            ], 404);
        }

        if($user->email_verified_at==NULL){
            $date = Carbon::now()->format('Y-m-d H:i:s');
            $user->email_verified_at = $date; // to enable the “email_verified_at field of that user be a current time stamp by mimicing the must verify email feature
            $user->save();
        }

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return response()->json([
            'message' => 'password changed correctly'
        ]);
    }

    public function change_password(Request $request){

        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'current_password' => 'required|string',
        ]);

        // Check email
        $user = User::where('email', $request->email)->first();

        if($user->deleted_at != NULL){
            return response([
                'message' => 'this user was deleted at '.$user->deleted_at,
            ], 404);
        }

        // Check password
        if(!$user || !Hash::check($request->current_password, $user->password)) {
            return response([
                'message' => 'Bad creds'
            ], 401);
        }

        $user->password=bcrypt($request->password);
        $user->save();

        return response()->json([
            'message' => 'Password updated succesfully'
        ],200);

    }
}
