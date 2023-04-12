<?php
namespace App\Http\Controllers;
use App\Enums\UserType;
use BenSampo\Enum\Rules\EnumValue;
use App\Http\Resources\UserResource;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\FirstAccessMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\Registered;
use Storage;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\DB;
use App\Events\RegisteredNewUser;

class AuthController extends Controller
{
    public function register(Request $request) {
        $request->validate([
            'email' => 'required|string|unique:users,email',
	        'role' => ['required', new EnumValue(UserType::class,false)],
	        'name' => 'required|string'
	    ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt(Str::random(8)),
            'type' => $request->role
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $token_confirmation = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token_confirmation,
            'created_at' => Carbon::now()
        ]);

        $response = [
            'user' => $user,
            'token' => $token
        ];

        event(new RegisteredNewUser($user, $token_confirmation));

        return response($response, 201);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $user = User::where('email', $request->email)->first();

        if($user->deleted_at != NULL){
            return response([
                'message' => 'this user was deleted at '.$user->deleted_at,
            ], 404);
        }

        // Check password
        if(!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Bad creds'
            ], 401);
        }

        if($user->profileImage!=NULL)
            $user->profileImage=$url = Storage::disk('s3')->temporaryUrl($user->profileImage, now()->addMinutes(60));

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => new UserResource($user),
            'token' => $token
        ];

        return response($response, 200);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }


}
