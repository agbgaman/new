<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Services\Statistics\UserService;
use App\Models\TranscribeLanguage;
use Aws\TranscribeService\TranscribeServiceClient;
use Aws\TranscribeService\Exception;
use Aws\Exception\AwsException;
use Aws\Laravel\AWSService;
use Aws\Credentials\Credentials;

class AWSSTTService
{

    private $client;
    private $api;
    private $aws;

    /**
     * Create Amazon Polly Client
     *
     *
     */
    public function __construct()
    {
//        $this->api = new UserService();
//
//        try {
//
//            $this->aws = new AWSService();
//            $credentials = new Credentials(config('services.aws.key'), config('services.aws.secret'));
//
//            $this->client = new TranscribeServiceClient([
//                'region'      => config('services.aws.region'),
//                'version'     => 'latest',
//                'credentials' => $credentials
//            ]);
//
//        } catch (AwsException $e) {
//            return response()->json(["exception" => "Credentials are incorrect. Please notify support team."], 422);
//            Log::error($e->getMessage());
//        }

    }


    /**
     * Call proper transcribe function
     *
     *
     */
    public function startTask(TranscribeLanguage $language, $job_name, $identify, $speakers, $extension, $s3_file_url)
    {
        $verify = $this->api->verify_license();

        if($verify['status']!=true){
            return false;
        }

        return $this->transcribeAudio($language, $job_name, $identify, $speakers, $extension, $s3_file_url);
    }


    /**
     * Transcribe audio file
     *
     * @return result
     */
    private function transcribeAudio(TranscribeLanguage $language, $job_name, $identify, $speakers, $extension, $s3_file_url)
    {
        if ($this->api->api_url != 'https://license.berkine.space/') {
            return;
        }

        $showSpeakers = ($identify == 'true') ? true : false;
        $speakers = ($speakers) ? (int)$speakers : 2;
        $upload = $this->aws->upload();
        if (!$upload['status']) { return false; }

        try {

            # Start Transcribe Job
            if ($showSpeakers) {
                $transcribe = $this->client->startTranscriptionJob([
                    'LanguageCode' => $language->language_code, 									# Language of the Content of Audio File - Valid Values: en-US | es-US | en-AU | fr-CA | en-GB | de-DE | pt-BR | fr-FR | it-IT | ko-KR | es-ES | en-IN | hi-IN | ar-SA | ru-RU | zh-CN | nl-NL | id-ID | ta-IN | fa-IR | en-IE | en-AB | en-WL | pt-PT | te-IN | tr-TR | de-CH | he-IL | ms-MY | ja-JP | ar-AE
                    'Media' => [
                        'MediaFileUri' => $s3_file_url,								                # Location of the File in S3 Bucket (Must be in the same region where Amazon Transcribe service is used)
                    ],
                    'Settings' => [
                        'MaxSpeakerLabels' => $speakers,										    # Optional:
                        'ShowSpeakerLabels' => $showSpeakers,										# Optional: Can be true || false
                    ],
                    'OutputBucketName' =>  config('services.aws.bucket'),			                # Bucket name for the Output JSON File
                    'TranscriptionJobName' => $job_name, 					                        # Required Transcription Job Name
                ]);

            } else {
                $transcribe = $this->client->startTranscriptionJob([
                    'LanguageCode' => $language->language_code, 									# Language of the Content of Audio File - Valid Values: en-US | es-US | en-AU | fr-CA | en-GB | de-DE | pt-BR | fr-FR | it-IT | ko-KR | es-ES | en-IN | hi-IN | ar-SA | ru-RU | zh-CN | nl-NL | id-ID | ta-IN | fa-IR | en-IE | en-AB | en-WL | pt-PT | te-IN | tr-TR | de-CH | he-IL | ms-MY | ja-JP | ar-AE
                    'Media' => [
                        'MediaFileUri' => $s3_file_url,								                # Location of the File in S3 Bucket (Must be in the same region where Amazon Transcribe service is used)
                    ],
                    'OutputBucketName' =>  config('services.aws.bucket'),			                # Bucket name for the Output JSON File
                    'TranscriptionJobName' => $job_name, 					                        # Required Transcription Job Name
                ]);
            }

            if ($transcribe['@metadata']['statusCode'] == '200') {
                $task_created = 'success';
            }


        } catch (\Exception $e) {
            return response()->json(["error" => "Create Transribe Task Error. Please try again or send a support request."], 422);
            Log::info($e->getMessage());
        }

        return  $task_created;
    }


    public function getTranscribeResults($task_id)
    {
        return $this->client->getTranscriptionJob([
            'TranscriptionJobName' => $task_id
        ]);
    }

}
