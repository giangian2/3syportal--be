<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class GoogleDriveController extends Controller
{
    protected $ClientId     = '843381078994-qhrd02ecu7das6p3jkvla9h32je1gkpv.apps.googleusercontent.com';
    protected $ClientSecret = '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';
    protected $refreshToken = '1//04Xsv2Vhx4xsDCgYIARAAGAQSNwF-L9Ir3aPJGbOkV7TyLiCRrIgzWVJuvTGs4k602iOdqMFDp8noaCx5d-xHayZcgd9zSwQrCrw';
    protected $rootFolderId = '1Q2s8LsvyYz6e9hm-L6bKMXYcqT7FQv3b';


    /*1yPy2Mg9iFJGs7hzEtBnsHALQs7bZcZ6P
    public function test(Request $request){

        //return Storage::disk('google')->files("/1Q2s8LsvyYz6e9hm-L6bKMXYcqT7FQv3b");

        //return Storage::disk('google')->directories("/1Q2s8LsvyYz6e9hm-L6bKMXYcqT7FQv3b");

        //return $this->createDirectory();
    }
    */


    public function createDirectory(string $dirname){
        $client = new \Google_Client();
        $client->setClientId($this->ClientId);
        $client->setClientSecret($this->ClientSecret);
        $client->refreshToken($this->refreshToken);

        $service = new \Google_Service_Drive($client);

        $fileMetadata = new \Google_Service_Drive_DriveFile([
            'name'     => $dirname,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => array($this->rootFolderId),
        ]);

        //GOOGLE CLIENT API LIBRARY ERROR, USE THIS LINE TO REMOVE THE ERROR
        unset($fileMetadata->exportLinks);

        $folder = $service->files->create($fileMetadata, ['fields' => 'id']);


        return $folder->id;
    }

    public function uploadFile(string $dirHash, string $storagePath, string $filename){
        return Storage::disk('google')->put('/'.$this->rootFolderId.'/'.$dirHash.'/'.$filename, Storage::disk('s3')->get($storagePath) , 'public');
    }


}
