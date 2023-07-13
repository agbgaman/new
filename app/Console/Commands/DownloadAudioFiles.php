<?php

namespace App\Console\Commands;

use App\Models\TranscribeResult;
use Illuminate\Console\Command;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class DownloadAudioFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audio:download';
    protected $description = 'Download audio files from S3';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $s3 = new S3Client([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
        ]);

        // Replace this query with the actual query to retrieve audio file links from your database
        $transcribeResults = TranscribeResult::select('id', 'file_url')
            ->whereNotNull('file_url')
            ->orderBy('id','DESC')
            ->take(30)
            ->where('user_id', 2)
            ->get();


        $downloadPath = storage_path('app/audio_files');

        if (!File::exists($downloadPath)) {
            File::makeDirectory($downloadPath, 0755, true);
        }

        foreach ($transcribeResults as $audioLink) {
            dd($audioLink);
            $s3Url = $audioLink->file_url; // Replace 's3_url' with the actual column name in your database
            $pathInfo = pathinfo($s3Url);
            $filename = $pathInfo['basename'];

            // Extract bucket and key from the S3 URL
            preg_match('/:\/\/([^\/]+)\/(.*)$/', $s3Url, $matches);
            $bucket = $matches[1];
            $key = $matches[2];

            try {
                $result = $s3->getObject([
                    'Bucket' => $bucket,
                    'Key'    => $key,
                ]);

                $fileContent = $result['Body'];

                // Save the downloaded file to your local storage
                Storage::put("audio_files/$filename", $fileContent);

                $this->info("Downloaded: $filename");

            } catch (AwsException $e) {
                $this->error("Error downloading $filename: " . $e->getMessage());
            }
        }

        $this->info('All audio files have been downloaded.');
    }
}
