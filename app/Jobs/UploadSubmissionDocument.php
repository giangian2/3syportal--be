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
     * @param User User
     * @param Submission Sub
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
        $cloud_spaces=DB::table('user_cloud_space')
                            ->where('user_id', $this->user->id)
                            ->where('space', 'drive')
                            ->count();

        if($cloud_spaces==0){
            $folder_id=GoogleDriveController::createDirectory(trim($this->user->name.$this->user->lastName));
            DB::table('user_cloud_space')->insert(['space'=> 'drive', 'dirname' => trim($this->user->name.$this->user->lastName), 'dirhash' => $folder_id, 'user_id' => $this->user->id]);
        }

        $folder_hash=DB::table('user_cloud_space')
                        ->where('user_id', $this->user->id)
                        ->where('space', 'drive')
                        ->value('dirhash');

        GoogleDriveController::uploadFile($folder_hash, $this->submission->document_path, $this->submission->id.$this->submission->document_name);


    }
}
