<?php

namespace App\Jobs;

use App\Models\Image;
use App\Models\User;
use Aws\S3\S3Client;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class UploadImageOnAWSBucket implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3; // Increase maximum number of attempts to 3

    protected $requestData;
    protected $filePaths;
    protected $status;
    protected $folderID;
    protected $name;
    protected $userID;

    public function __construct($name, $userID,$status,$folderID,$filePaths)
    {

        $this->name         = $name;
        $this->userID       = $userID;
        $this->status       = $status;
        $this->folderID     = $folderID;
        $this->filePaths    = $filePaths;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get the client for the S3 bucket
        $s3 = S3Client::factory([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest'
        ]);

        foreach ($this->filePaths as $filePath) {
            try {
                // Get the file name from the path
                $fileName = basename($filePath);

                // Upload the image to the S3 bucket
                $result = $s3->putObject([
                    'Bucket' => env('AWS_BUCKET'),
                    'Key' => $fileName,
                    'SourceFile' => Storage::path($filePath),
                    'ACL' => 'public-read'
                ]);

                $url = $s3->getObjectUrl(env('AWS_BUCKET'), $fileName);

                $image = Image::create([
                    'user_id' => $this->userID,
                    'image' => $url,
                    'name' => $this->name,
                    'status' => $this->status,
                    'folder_id' => $this->folderID,
                ]);
                // Get the current date and time in the 'Asia/Kolkata' timezone
                $date = new \DateTime('now', new \DateTimeZone('Asia/Kolkata'));

                $image->created_at = $date;
                $image->timestamps = false; // To disable update_at field update

                $image->save(); // Save the model
                // Once the file is uploaded, delete it from the temporary folder
                Storage::delete($filePath);

            } catch (\Exception $e) {
                \Log::error('Error uploading file: ' . $filePath . ', Error message: ' . $e->getMessage());
                // You can add any additional error handling here if needed
            }
        }
    }

}

