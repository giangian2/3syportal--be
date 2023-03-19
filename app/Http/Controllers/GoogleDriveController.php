<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;

class GoogleDriveController extends Controller
{
    public static function createDirectory(string $dirname){
        //return folder id
    }

    public static function uploadFile(string $dirHash, mixed $file, string $filename){
        //return file id
    }
}
