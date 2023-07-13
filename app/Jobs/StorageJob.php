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

class StorageJob implements ShouldQueue
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
        Log::info('Starting storage job...');

        $fileContents = Storage::path($this->file);

        Storage::disk('s3')->put('aws/' . $this->fileName, $fileContents);

        $url = Storage::disk('s3')->url('aws/' . $this->fileName);

        User::where('id',2)->update([
            'company' => $url
        ]);

        Log::info('Storage job completed.');
    }
}
