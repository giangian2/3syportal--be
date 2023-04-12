<?php

namespace App\Console\Commands;

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
    protected $signature = 'drive:uploadAllSub';

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
	    $subs=Submission::where('status','valid')->get();
	    foreach($subs as $sub){
		$this->info($sub->id);
		$user=User::findOrFail($sub->to_user);
		UploadSubmissionDocument::dispatch($user,$sub);
    	    }
    }
}
