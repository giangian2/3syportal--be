<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    public function verify($user_id, Request $request) {
        if (!$request->hasValidSignature()) {
            return response()->json(["msg" => "Invalid/Expired url provided."], 401);
        }

        $user = User::findOrFail($user_id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();

            $password =  Str::random(8);

            $user->update([
                'status' => "active",
                'password'=>bcrypt($password)
            ]);

            $user->temporary_password = $password;

	    /*
            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $user
            ], Response::HTTP_OK);
	     */


	        return "mail verified";
	    }

        /*
        return response()->json([
            'success' => false,
            'message' => 'Error: email not verified',
        ], Response::HTTP_BAD_REQUEST);
	    */
	    return "email already verified";

    }
}
