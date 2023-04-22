<?php

namespace App\Http\Controllers;

use App\Http\Resources\TalentResource;
use Illuminate\Http\Request;
use App\Models\Talent;
use App\Models\Verticality;
use App\Models\TalentVerticality;
use App\Models\Social;
use App\Models\SocialInfo;

class TalentController extends Controller
{
    /**
     * @param Request
     * @return Response
     */

    public function index(Request $request)
    {
        //Talent::find($talent->id)->with('social_infos')->findOrFail($talent->id)
        $talents=Talent::select('id','name', 'surname', 'mediaKit_src', 'birth_date', 'email', 'phone')
                            ->with('verticalities')
                            ->with('social_infos')->get();

        return response()->json([
            'talents' => TalentResource::collection($talents)
        ], 200);

    }

    public function store()
    {

    }

    public function show($id)
    {

    }

    public function delete($id)
    {

    }

    public function update($id)
    {

    }
}
