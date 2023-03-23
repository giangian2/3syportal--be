<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class GoogleDriveController extends Controller
{
    /*1yPy2Mg9iFJGs7hzEtBnsHALQs7bZcZ6P
    public function test(Request $request){

        //return Storage::disk('google')->files("/1Q2s8LsvyYz6e9hm-L6bKMXYcqT7FQv3b");

        //return Storage::disk('google')->directories("/1Q2s8LsvyYz6e9hm-L6bKMXYcqT7FQv3b");

        //return $this->createDirectory();
    }
    */


    public function createDirectory(string $dirname){
        $client = new \Google_Client();
        $client->setClientId(env("GOOGLE_DRIVE_CLIENT_ID"));
        $client->setClientSecret(env("GOOGLE_DRIVE_CLIENT_SECRET"));
        $client->refreshToken(env("GOOGLE_DRIVE_REFRESH_TOKEN"));

        $service = new \Google_Service_Drive($client);

        $fileMetadata = new \Google_Service_Drive_DriveFile([
            'name'     => $dirname,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => array(env("GOOGLE_DRIVE_FOLDER")),
        ]);

        //GOOGLE CLIENT API LIBRARY ERROR, USE THIS LINE TO REMOVE THE ERROR
        unset($fileMetadata->exportLinks);

        $folder = $service->files->create($fileMetadata, ['fields' => 'id']);


        return $folder->id;
    }

    public function uploadFile(string $dirHash, string $storagePath, string $filename){
        return Storage::disk('google')->put('/'.env("GOOGLE_DRIVE_FOLDER").'/'.$dirHash.'/'.$filename, Storage::disk('s3')->get($storagePath) , 'public');
    }


}
