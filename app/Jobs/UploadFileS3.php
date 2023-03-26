<?php

namespace App\Jobs;

use App\Http\Controllers\FileController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadFileS3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     * @param string path
     * @param string filename
     * @return void
     */

    protected string $path;
    protected string $file;

    public function __construct(string $path, string $file)
    {
        $this->path=$path;
        $this->file=$file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        FileController::store_file($this->path, $this->file, 's3');
    }
}
