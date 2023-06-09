<?php

namespace App\Http\Controllers;

use App\Jobs\UploadFileS3;
use App\Events\DeletedUser;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Enums\UserType;
use App\Http\Resources\UserResource;

class AccountController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $sender=auth()->user();

        if($sender->type==UserType::Manager()){
            $users = User::where('type','=',0)
                        ->where('deleted_at','=', NULL)
                        ->where('id','!=',$sender->id)
                        ->get();
        }else if(UserType::fromValue($sender->type)==UserType::Admin()){
            $users = User::where('type','<',UserType::Admin)
                        ->where('deleted_at','=', NULL)
                        ->where('id','!=',$sender->id)
                        ->get();
        }

        return response()->json([
            'status' => true,
            'users' => UserResource::collection($users)
        ]);
    }


    public function show(Request $request, User $user)
    {
        if(!Gate::allows('view', $user)){
            abort(403);
        }

        $user=User::where('id', $user->id)->first();

        return response()->json([
            'status' => true,
            'user' => new UserResource($user)
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'nullable|string',
            'lastName' => 'nullable|string',
            'birthDate' => 'nullable|date',
            'birthPlace' => 'nullable|string',
            'telephoneNumber' => 'nullable|string',
            'fiscalCode' => 'nullable|string',
            'ibanCode' => 'nullable|string',
            'bank' => 'nullable|string',
	        'contractType' => 'nullable|string',
	        'userRole' => 'nullable|string',
            'partitaIva' => 'nullable|string'
        ]);

        if(!Gate::allows('view', $user)){
            abort(403);
        }

        $user->update($request->all());


        return response()->json([
            'status' => true,
            'message' => "user Updated successfully!",
            'user' => new UserResource($user)
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(User $user)
    {
        FileController::delete_folder('/users/'. $user->id);

        $receiver=User::find($user->id);

	$user->delete();

        event(new DeletedUser($receiver));

        return response()->json([
            'status' => true,
            'message' => "User Deleted successfully!",
        ], 200);
    }

    public function update_profile_image(Request $request, User $user){

        $request->validate([
            'profileImage' => 'required'
        ]);

        if(!Gate::allows('view', $user)){
            abort(403);
        }

        $file=FileController::decode_base64($request->profileImage);
        $extension=FileController::get_file_extension($request->profileImage);
        $file_path = 'users/'.$user->id.'/profile.'.$extension;
        FileController::store_file($file_path, $file, 's3');

        $this->dispatch(new UploadFileS3($file_path, $file));

        $user->profileImage=$file_path;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => "user Updated successfully!",
            'user' => $user
        ], 200);

    }

}

