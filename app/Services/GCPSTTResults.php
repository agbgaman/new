<?php

namespace App\Services;

use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\SpeakerDiarizationConfig;
use GPBMetadata\Google\Cloud\Speech\V1\CloudSpeech;
use Google\Cloud\Speech\V1p1beta1\RecognitionConfig\AudioEncoding;
use Illuminate\Support\Facades\Log;
use Exception;

class GCPSTTResults
{
    private $speech_client;

    /**
     * Initialize GCP client
     *
     * 
     */
    public function __construct()
    {
        try {

            if (config('services.gcp.key_path')) {
                $credentials = config('services.gcp.key_path');

                $this->speech_client = new SpeechClient([
                    'credentials' => json_decode(file_get_contents($credentials), true),
                ]);  

                CloudSpeech::initOnce();
            }
                     

        } catch (Exception $e) {
            return response()->json(["exception" => "Credentials are incorrect. Please notify support team."], 422);
            Log::error($e->getMessage());
        }
    }


    /**
     * Get Results
     *
     */
    public function getTranscribeResults($name)
    {
        $operation = $this->speech_client->resumeOperation($name, 'longRunningRecognize');

        if ($operation->isDone()) {

            if ($operation->operationSucceeded()) {
                $response = $operation->getResult();
                $results = json_decode($response->serializeToJsonString(), true);

                $reply['payload'] = $results['results'];
                $reply['status'] = 'success';

                return $reply;
                
            } else {
               $error = $operation->getError();
               Log::error($error);

               $reply['payload'] = $error;
               $reply['status'] = 'failed';

               return $reply;
            }
        } 
    }
}