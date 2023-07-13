<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Aws\S3\S3Client;

class AWSStorageAudioJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $file;
    protected $fileName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file, $fileName)
    {
        $this->file     = $file;
        $this->fileName = $fileName;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new S3Client([
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
        ]);

        $file = Storage::path($this->file);
        $fileContents = file_get_contents($file);
        $result = $client->putObject([
            'Bucket' => env('AWS_BUCKET'),
            'Key'    => 'aws/' . $this->fileName,
            'Body'   => $fileContents,
            'ACL'    => 'public-read'
        ]);
        $url = $result['ObjectURL'];


    }
}
