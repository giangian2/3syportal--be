<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PasswordController extends Controller
{
    public function send_reset_password_notification(Request $request) {

        $credentials = $request->only('email');
        $response =  Password::sendResetLink($credentials);

        if( $response == Password::RESET_LINK_SENT){
            $message = "Mail send successfully";
        }else{
            $message = "Email could not be sent to this email address";
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ], Response::HTTP_OK);

    }


    public function reset(Request $request)
    {

        $credentials = $request->only('email', 'token', 'password', 'password_confirmation');
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $reset_password_status = Password::reset($credentials, function ($user, $password) {
            $user->password = bcrypt($password);
            $user->save();
        });


        if ($reset_password_status == Password::INVALID_TOKEN) {
            return response()->json(["msg" => "Invalid token provided"], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Password has been successfully changed'
        ], Response::HTTP_OK);
    }
}
