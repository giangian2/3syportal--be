<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use Illuminate\Http\Request;

class TestGoogleDriveController extends Controller
{
    protected $client;
    protected $folder_id;
    protected $service;
    protected $ClientId     = '843381078994-qhrd02ecu7das6p3jkvla9h32je1gkpv.apps.googleusercontent.com';
    protected $ClientSecret = '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';
    protected $refreshToken = '1//04Xsv2Vhx4xsDCgYIARAAGAQSNwF-L9Ir3aPJGbOkV7TyLiCRrIgzWVJuvTGs4k602iOdqMFDp8noaCx5d-xHayZcgd9zSwQrCrw';
    /*1yPy2Mg9iFJGs7hzEtBnsHALQs7bZcZ6P*/
    public function test(Request $request){

        //return Storage::disk('google')->files("/1Q2s8LsvyYz6e9hm-L6bKMXYcqT7FQv3b");
        return Storage::disk('google')->put('/1Q2s8LsvyYz6e9hm-L6bKMXYcqT7FQv3b/1xRuRzKHeclM8CcFOAB8NruBPkrtT9ebc/test.pdf', Storage::disk('s3')->get('users/10/submissions/87/file.pdf') , 'public');
        //return Storage::disk('google')->directories("/1Q2s8LsvyYz6e9hm-L6bKMXYcqT7FQv3b");

        //return $this->createDirectory();
    }

    private function createDirectory(){
        $this->client = new \Google_Client();
        $this->client->setClientId($this->ClientId);
        $this->client->setClientSecret($this->ClientSecret);
        $this->client->refreshToken($this->refreshToken);

        $this->service = new \Google_Service_Drive($this->client);

        // we cache the id to avoid having google creating
        // a new folder on each time we call it,
        // because google drive works with 'id' not 'name'
        // & thats why u could have duplicated folders under the same name
        return $this->create_folder();
    }

    protected function create_folder()
    {
        $fileMetadata = new \Google_Service_Drive_DriveFile([
            'name'     => 'DIR',
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => array('1Q2s8LsvyYz6e9hm-L6bKMXYcqT7FQv3b'),
        ]);
        unset($fileMetadata->exportLinks);
        $folder = $this->service->files->create($fileMetadata, ['fields' => 'id']);

        return $folder->id;
    }
}
