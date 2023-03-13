<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

class FileController extends Controller
{
    public static function decode_base64($file){

        $image_64 = $file; //your base64 encoded data
        $replace = substr($image_64, 0, strpos($image_64, ',')+1); 
        $image = str_replace($replace, '', $image_64); 
        $image = str_replace(' ', '+', $image); 
        return $image;
    }

    public static function get_file_extension($file){

        $image_64 = $file;
        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
        return $extension;
    }

    public static function store_file($path, $file, $disk){
        try{
            Storage::disk($disk)->put($path, base64_decode($file));
        }catch(Exception $e){
            return response()->json([
                'message' => $e
            ],500);
        }
    }

    public static function delete_folder($path){
        try{
            Storage::disk('s3')->deleteDirectory($path);
        }catch(Exception $e){
            return response()->json([
                'message' => $e
            ],500);
        }
    }
}
