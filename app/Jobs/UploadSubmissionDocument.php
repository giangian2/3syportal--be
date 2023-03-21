<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\GoogleDriveController;
use App\Models\Submission;

class UploadSubmissionDocument implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected User $user;
    protected Submission $submission;

    public function __construct(User $user, Submission $submission)
    {
        $this->user=$user;
        $this->submission=$submission;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $gdriveController=new GoogleDriveController();
        $cloud_spaces=DB::table('user_cloud_space')
                            ->where('user_id', $this->user->id)
                            ->where('space', 'drive')
                            ->count();

        if($cloud_spaces==0){
            $folder_id=$gdriveController->createDirectory($this->user->id);
            DB::table('user_cloud_space')->insert(['space'=> 'drive', 'dirname' => $folder_id, 'dirhash' => $folder_id, 'user_id' => $this->user->id]);
        }

        $folder_hash=DB::table('user_cloud_space')
                        ->where('user_id', $this->user->id)
                        ->where('space', 'drive')
                        ->value('dirhash');

        $gdriveController->uploadFile($folder_hash, /*$this->submission->document_path*/ 'users/10/submissions/87/file.pdf', "testName.pdf");


    }
}
