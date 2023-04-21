<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Talent;
use App\Models\Verticality;
use App\Models\TalentVerticality;

class TalentController extends Controller
{
    public function test()
    {
        $talent = Talent::create([
            'name' => 'testName',
        ]);

        $verticality = Verticality::create([
            'description' => 'bho5',
        ]);

        $talent_verticality = TalentVerticality::create([
            'talent_id' => $talent->id,
            'verticality_id' => $verticality->id,
        ]);


        return response()->json([
            'res' => Talent::find($talent->id)->with('verticalities')->findOrFail($talent->id)
        ], 200);
    }
}
