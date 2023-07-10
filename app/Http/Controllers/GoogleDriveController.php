<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class GoogleDriveController extends Controller
{
    
    public function test(Request $request){

	return "ok";
        //return Storage::disk('google')->directories("/1YG-PE5TF-d1xjllbqji_QSPyI1VIE3hQ");
	//$this->createDirectory("test2");
        //return Storage::disk('google')->directories("/1Q2s8LsvyYz6e9hm-L6bKMXYcqT7FQv3b");
    }
    


    public static function createDirectory(string $dirname){

        $client = new \Google_Client();
        $client->setClientId('843381078994-qhrd02ecu7das6p3jkvla9h32je1gkpv.apps.googleusercontent.com');
        $client->setClientSecret('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');
        $client->refreshToken('1//041eWxaYtmx8vCgYIARAAGAQSNwF-L9Ir371pPtvZqoSn8NciQWsupgyecUb0xu6CjSC7VxErKNji1ts0rA89A2JQsIRtytBbJK4');

        $service = new \Google_Service_Drive($client);

        $fileMetadata = new \Google_Service_Drive_DriveFile([
            'name'     => $dirname,
            'mimeType' => 'application/vnd.google-apps.folder',
	    'parents' => array('1YG-PE5TF-d1xjllbqji_QSPyI1VIE3hQ'),
	    'supportsAllDrives' => true,
        ]);

        //GOOGLE CLIENT API LIBRARY ERROR, USE THIS LINE TO REMOVE THE ERROR
        unset($fileMetadata->exportLinks);

        $folder = $service->files->create($fileMetadata, ['fields' => 'id']);

        return $folder->id;
    }

    public static function uploadFile(string $dirHash, string $storagePath, string $filename){
	    $file=Storage::disk('s3')->get($storagePath);
	    if($file==null){
		throw new \ErrorException('Error found');
	    }
	    if(!Storage::disk('google')->put('/'.'1YG-PE5TF-d1xjllbqji_QSPyI1VIE3hQ'.'/'.$dirHash.'/'.$filename, Storage::disk('s3')->get($storagePath) , 'public')){
		    Log::error("Cant upload file");
	    }
    }


}
