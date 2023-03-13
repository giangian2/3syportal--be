<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;

class GoogleDriveController extends Controller
{
    public function test(Request $request){
        $dirs = Storage::disk('google')->allDirectories();
        return $dirs;
    }
}
