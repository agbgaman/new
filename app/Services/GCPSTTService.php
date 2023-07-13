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
use Spatie\Backup\Helpers\Backup;
use App\Services\Statistics\UserService;
use App\Models\TranscribeLanguage; 
use Exception;

class GCPSTTService 
{
    private $speech_client;
    private $storage_client;
    private $api;

    /**
     * Initialize GCP client
     *
     * 
     */
    public function __construct()
    {
        $this->api = new UserService();

        $verify = $this->api->verify_license();

        if($verify['status']!=true){
            return false;
        }

        try {

            if (config('services.gcp.key_path')) {
                $credentials = config('services.gcp.key_path');

                $this->speech_client = new SpeechClient([
                    'credentials' => json_decode(file_get_contents($credentials), true),
                ]);  

                $this->storage_client = new StorageClient([
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
     * Call proper transcribe function
     *
     * 
     */
    public function startTask(TranscribeLanguage $language, $job_name, $extension, $source, $duration, $audio_type, $task_type, $identify = null, $speakers = null,)
    {
        $verify = $this->api->verify_license();

        if($verify['status']!=true){
            return false;
        }
        
        $objectName = 'gcp/' . $job_name . '.' . $extension;
        $file = fopen($source, 'r');
        $supported = [67, 78, 85, 88, 89, 95, 100, 109, 111, 129, 133, 154];
        if (in_array($language->id, $supported)) {
            $showSpeakers = ($identify == 'true') ? true : false;
        } else {
            $showSpeakers = false;
        }
              

        try {
            $bucket = $this->storage_client->bucket(config('services.gcp.bucket'));
            $object = $bucket->upload($file, [
                'name' => $objectName,
                'predefinedAcl' => 'PUBLICREAD',
            ]);

            $object->update([
                'contentDisposition' => 'attachment',
                'contentType' => $audio_type
            ]);

            $bucket->update([
                'cors' => [
                    [
                        'method' => ['*'],
                        'origin' => ['*'],
                        'responseHeader' => ['*'],
                        'maxAgeSeconds' => 36000,
                    ]
                ]
            ]);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $response['status'] = 'failed';
        }

        $backup = new Backup();
        $upload = $backup->upload();
        if (!$upload['status']) { return false; }

        $uri = 'gs://' . config('services.gcp.bucket') . '/' . $objectName;

        switch ($extension) {
            case 'wav':
                $encoding = AudioEncoding::LINEAR16;
                break;
            case 'mp3':
                $encoding = AudioEncoding::MP3;
                break;
            case 'flac':
                $encoding = AudioEncoding::FLAC;
                break;
            case 'ogg':
                $encoding = AudioEncoding::OGG_OPUS;
                break;
            default:
                $encoding = AudioEncoding::LINEAR16;
                break;
        }

        // change these variables if necessary
        $languageCode = $language->language_code;


        if ($duration <= 60) {
            return $this->transcribeAudio($encoding, $uri, $languageCode, $objectName, $task_type, $showSpeakers, $speakers);
        } else {
            return $this->transcribeAudioLong($encoding, $uri, $languageCode, $objectName, $task_type, $showSpeakers, $speakers);  
        }                  
    }


    /**
     * Transcribe short audio files
     *
     * @return result 
     */
    public function transcribeAudio($encoding, $uri, $languageCode, $objectName, $task_type, $showSpeakers = null, $speakers = null)
    {   
        if ($this->api->api_url != 'https://license.berkine.space/') {
            return;
        }        
        
        // set string as audio content
        $audio = (new RecognitionAudio())
            ->setUri($uri);
        
        if ($showSpeakers) {
            $speaker = (new SpeakerDiarizationConfig())
                ->setEnableSpeakerDiarization(true)
                ->setMaxSpeakerCount($speakers);
            
            if ($task_type == 'record') {
                $config = (new RecognitionConfig())
                ->setEncoding($encoding)
                ->setLanguageCode($languageCode)
                ->setSampleRateHertz(48000)
                ->setDiarizationConfig($speaker)
                ->setEnableAutomaticPunctuation(true);

            } else {
                $config = (new RecognitionConfig())
                ->setEncoding($encoding)
                ->setLanguageCode($languageCode)
                ->setDiarizationConfig($speaker)
                ->setEnableAutomaticPunctuation(true);
            }            

        } else {

            if ($task_type == 'record') {
                $config = (new RecognitionConfig())
                ->setEncoding($encoding)
                ->setLanguageCode($languageCode)
                ->setSampleRateHertz(48000)
                ->setEnableAutomaticPunctuation(true);
            } else {
                $config = (new RecognitionConfig())
                ->setEncoding($encoding)
                ->setLanguageCode($languageCode)
                ->setEnableAutomaticPunctuation(true);
            }              
        }   
        
        try {
            $operation = $this->speech_client->recognize($config, $audio);
            $transcript = '';
            $raw = '';

            # Print most likely transcription                                  
            foreach ($operation->getResults() as $result) { 

                $results = json_decode($result->serializeToJsonString(), true);
                $check_transcript = $results['alternatives'][0];

                if(!array_key_exists('transcript', $check_transcript)) {
                    $raw = json_encode($results, true);  
                } 

                $alternatives = $result->getAlternatives();     
                $mostLikely = $alternatives[0];            
                $transcript .= $mostLikely->getTranscript();
            }

            $response['status'] = 'success';
            $response['job_status'] = 'COMPLETED';
            $response['transcript'] = $transcript;
            $response['gcp_task'] = '';
            $response['raw'] = $raw;
            $response['url'] = 'https://storage.googleapis.com/' . config('services.gcp.bucket') . '/' . $objectName;

        } catch(Exception $e) {
            Log::error($e->getMessage());
            $response['status'] = 'failed';
            $response['message'] = $e->getMessage();
            return $response;

        } finally {
            $this->speech_client->close();
        }
        
        return $response;
    }


    /**
     * Transcribe long audio files
     *
     * @return result
     */
    public function transcribeAudioLong($encoding, $uri, $languageCode, $objectName, $task_type, $showSpeakers = null, $speakers = null)
    {   
        $verify = $this->api->verify_license();

        if($verify['status']!=true){
            return false;
        }

        // set string as audio content
        $audio = (new RecognitionAudio())
            ->setUri($uri);

        if ($showSpeakers) {
            $speaker = (new SpeakerDiarizationConfig())
                ->setEnableSpeakerDiarization(true)
                ->setMinSpeakerCount(2)
                ->setMaxSpeakerCount($speakers);
            
            if ($task_type == 'record') {
                $config = (new RecognitionConfig())
                ->setEncoding($encoding)
                ->setLanguageCode($languageCode)
                ->setSampleRateHertz(48000)
                ->setDiarizationConfig($speaker)
                ->setEnableAutomaticPunctuation(true);
            } else {
                $config = (new RecognitionConfig())
                ->setEncoding($encoding)
                ->setLanguageCode($languageCode)
                ->setDiarizationConfig($speaker)
                ->setEnableAutomaticPunctuation(true);
            }

        } else {
            $config = (new RecognitionConfig())
                ->setEncoding($encoding)
                ->setLanguageCode($languageCode)
                ->setEnableAutomaticPunctuation(true);                
        } 

        try {
            $operation = $this->speech_client->longRunningRecognize($config, $audio);

            $name = $operation->getName();
                
            $response['status'] = 'success';
            $response['job_status'] = 'IN_PROGRESS';
            $response['transcript'] = '';
            $response['gcp_task'] = $name;
            $response['raw'] = false;
            $response['url'] = 'https://storage.googleapis.com/' . config('services.gcp.bucket') . '/' . $objectName;

        } catch(Exception $e) {
            Log::error($e->getMessage());
            $response['status'] = 'failed';
            $response['message'] = $e->getMessage();
            return $response;
        } finally {
            $this->speech_client->close();
        }
        

        return $response;
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

        } else {
            $reply['status'] = 'pending';
            return $reply;
        }
    }

    /**
     * Delete Storage object
     *
     */
    public function deleteObject($task_id, $extension)
    {
        $objectName = 'gcp/' . $task_id . '.' . $extension;
        $bucket = $this->storage_client->bucket(config('services.gcp.bucket'));
        $object = $bucket->object($objectName);
        $object->delete();
    }
}