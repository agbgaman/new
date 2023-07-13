<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Aws\TranscribeService\TranscribeServiceClient;  
use Aws\Exception\AwsException;
use Aws\Credentials\Credentials;

class AWSSTTResults
{

    private $client;

    /**
     * Create Amazon Polly Client
     *
     * 
     */
    public function __construct()
    {   
        try {

            $credentials = new Credentials(config('services.aws.key'), config('services.aws.secret'));

            $this->client = new TranscribeServiceClient([
                'region'      => config('services.aws.region'),
                'version'     => 'latest',
                'credentials' => $credentials
            ]);

        } catch (AwsException $e) {
            return response()->json(["exception" => "Credentials are incorrect. Please notify support team."], 422);
            Log::error($e->getMessage());
        }

    }
    
    
    public function getTranscribeResults($task_id)
    {
        return $this->client->getTranscriptionJob([
            'TranscriptionJobName' => $task_id
        ]);
    }

}