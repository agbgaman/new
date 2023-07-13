<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Services\AWSSTTResults;
use App\Services\GCPSTTResults;
use App\Models\TranscribeResult;

class ProcessTranscribeTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transcribe:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Transcribe Task Results';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Clean locally stored audio result files based on the set date.
     *
     * @return int
     */
    public function handle()
    {
        $aws = new AWSSTTResults();
        $gcp = new GCPSTTResults();

        # Run each AWS Job Name
        $awsResult = TranscribeResult::where('status', 'IN_PROGRESS')->where('vendor', 'aws_audio')->get();
        
        foreach($awsResult as $row) {

            # Get Transcribe Results
            try {
                $results = $aws->getTranscribeResults($row['task_id']);

                if ($results['@metadata']['statusCode'] == 200) {            

                    # Assign needed transcribe results
                    $status = $results['TranscriptionJob']['TranscriptionJobStatus'];    
            
                    # If job complete, delete job name
                    if ($status == 'COMPLETED') {

                        # Create s3 object key name
                        $key = $row['task_id'] . '.json';                
                        $response = Storage::disk('s3')->get($key);                
                        $transcript = json_decode($response, true); 
                        $words = count(preg_split('/\s+/', strip_tags($transcript['results']['transcripts'][0]['transcript']))); 

                        $row->update([
                            'status' => 'COMPLETED',
                            'text' => $transcript['results']['transcripts'][0]['transcript'],
                            'raw' => $transcript,
                            'words' => $words,
                        ]);
            
                    } elseif ($status == 'FAILED') {
                        $row->update([
                            'status' => 'FAILED',
                            'text' => $results['FailureReason'],
                            'raw' => $results['FailureReason'],
                        ]);
                    }
                
                }

            } catch (\Exception $e) {
                \Log::error('AWS ID ' . $row['task_id'] . ': ' . $e->getMessage());
                $row->update([
                    'status' => 'FAILED',
                    'text' => 'There was an error with the transcription task',
                    'raw' => 'There was an error with the transcription task',
                ]);
            }
            
            
        }

        # Run each GCP Job Name
        $gcpResult = TranscribeResult::where('status', 'IN_PROGRESS')->where('vendor', 'gcp_audio')->get();
        
        foreach($gcpResult as $row) {

            # Get Transcribe Results
            try {
                $response = $gcp->getTranscribeResults($row['gcp_task']);

                if (isset($response)) {

                    if ($response['status'] == 'success') {
                        $transcript = '';
                        $raw = ''; 
        
                        if ($row['speaker_identity'] === 'false') {
                            foreach ($response['payload'] as $value) {     
                                if ($value['alternatives'][0]['transcript']) {
                                    $transcript .= $value['alternatives'][0]['transcript'];   
                                }       
                            }

                            $raw = $response['payload'];
        
                        } else {
                            foreach ($response['payload'] as $value) {      
                                $check_transcript = $value['alternatives'][0];
                                if(array_key_exists('transcript', $check_transcript)) {
                                    $transcript .= $value['alternatives'][0]['transcript'];  
                                } else {
                                    $raw = $value;
                                }                           
                            }
                        }
                        
                        $words = count(preg_split('/\s+/', strip_tags($transcript))); 
        
                        $row->update([
                            'status' => 'COMPLETED',
                            'text' => $transcript,
                            'raw' => json_encode($raw),
                            'words' => $words,
                        ]);
        
                    } elseif ($response['status'] == 'failed') {
                        $row->update([
                            'status' => 'FAILED',
                            'text' => $response['payload'],
                            'raw' => $response['payload'],
                        ]);

                    } else {
                        \Log::info('still processing');
                    }  
                }   

            } catch (\Exception $e) {
                \Log::error('GCP ID ' . $row['gcp_task'] . ': ' . $e->getMessage());
                $row->update([
                    'status' => 'FAILED',
                    'text' => 'There was an error with the transcription task',
                    'raw' => 'There was an error with the transcription task',
                ]);
            }
                   
        }
    }
}
