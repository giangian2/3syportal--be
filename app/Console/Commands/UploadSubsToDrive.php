<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Command;
use App\Http\Controllers\GoogleDriveController;
use App\Models\User;
use App\Models\Submission;
use App\Jobs\UploadSubmissionDocument;
use DB;

class UploadSubsToDrive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drive:uploadSub {subID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

	    $this->info("started...");
	    $subs=Submission::where('id',$this->argument('subID'))->get();
	    
	    foreach($subs as $sub){
		    $user=User::find($sub->to_user);
		    
		    $cloud_spaces=DB::table('user_cloud_space')
                            ->where('user_id', $user->id)
                            ->where('space', 'drive')
                            ->count();

        	    if($cloud_spaces==0){
            		$folder_id=GoogleDriveController::createDirectory(trim($user->name.$user->lastName));
            		DB::table('user_cloud_space')->insert(['space'=> 'drive', 'dirname' => trim($user->name.$user->lastName), 'dirhash' => $folder_id, 'user_id' => $user->id]);
        	    }

        	    $folder_hash=DB::table('user_cloud_space')
                        ->where('user_id', $user->id)
                        ->where('space', 'drive')
                        ->value('dirhash');

        	    GoogleDriveController::uploadFile($folder_hash, $sub->document_path, preg_replace('/[^A-Za-z0-9]/', '', $sub->id.$sub->document_name));
    	    }
	   
	        
    }
}
