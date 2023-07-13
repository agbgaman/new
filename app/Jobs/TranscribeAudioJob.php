<?php

namespace App\Jobs;

use App\Models\TranscribeResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TranscribeAudioJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $userId;
    protected $language;
    protected $fileUrl;
    protected $languageFlag;
    protected $taskId;
    protected $vendorImg;
    protected $vendor;
    protected $words;
    protected $text;
    protected $length;
    protected $audioType;
    protected $planType;
    protected $status;
    protected $mode;
    protected $project;
    protected $id;
    protected $idType;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        $userId,
        $language,
        $fileUrl,
        $languageFlag,
        $taskId,
        $vendorImg,
        $vendor,
        $words,
        $text,
        $length,
        $audioType,
        $planType,
        $status,
        $mode,
        $project,
        $id,
        $idType,
    )
    {
        $this->userId = $userId;
        $this->language = $language;
        $this->fileUrl = $fileUrl;
        $this->languageFlag = $languageFlag;
        $this->taskId = $taskId;
        $this->vendorImg = $vendorImg;
        $this->vendor = $vendor;
        $this->words = $words;
        $this->text = $text;
        $this->length = $length;
        $this->audioType = $audioType;
        $this->planType = $planType;
        $this->status = $status;
        $this->mode = $mode;
        $this->project = $project;
        $this->id = $id;
        $this->idType = $idType;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $updateData = [
            'user_id'           => $this->userId,
            'language'          => $this->language,
            'file_url'          => $this->fileUrl,
            'language_flag'     => $this->languageFlag,
            'task_id'           => $this->taskId,
            'vendor_img'        => $this->vendorImg,
            'vendor'            => $this->vendor,
            'words'             => $this->words,
            'text'              => $this->text,
            'length'            => $this->length,
            'audio_type'        => $this->audioType,
            'plan_type'         => $this->planType,
            'status'            => $this->status,
            'mode'              => $this->mode,
            'project'           => $this->project,
        ];

        if ($this->idType === 'image') {
            $updateData['image_id'] = $this->id;
            $result = TranscribeResult::updateOrCreate(
                ['image_id' => $this->id],
                $updateData
            );
        } else {
            $updateData['text_id'] = $this->id;
            $result = TranscribeResult::updateOrCreate(
                ['text_model_id' => $this->id],
                $updateData
            );
        }
    }

}

