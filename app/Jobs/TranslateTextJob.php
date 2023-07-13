<?php

namespace App\Jobs;

use App\Http\Controllers\Admin\TextController;
use App\Models\TextModel;
use Aws\Comprehend\ComprehendClient;
use Aws\Translate\TranslateClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class TranslateTextJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $name;
    protected $folderId;
    protected $status;

    public function __construct($data, $name,$folderId,$status)
    {
        $this->data         = $data;
        $this->name         = $name;
        $this->folderId     = $folderId;
        $this->status       = $status;
    }

    public function handle()
    {

        try {
            DB::beginTransaction();

            foreach ($this->data as $text) {
//                $textData = $this->autoTranslateToEnglish($text['text']);
                $text = TextModel::create([
                    'text'              => $text['text'],
                    'name'              => $text['name'],
                    'folder_id'         => $this->folderId,
                    'user_id'           => auth()->id(),
                    'status'            => $this->status,
                    'type'              => 'text_translation',
                    'translated_text'   => $text['translated_text']
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', __('CSV Text was successfully created'));
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the error message or return it to the user
            return redirect()->back()->with('error', __('An error occurred while creating the CSV Text: ' . $e->getMessage()));
        }
    }

    /**
     * @param $sourceText
     * @param $sourceLanguage
     * @param $targetLanguage
     * @return mixed|null
     * Translate text into english
     */
    public static function translate($sourceText, $sourceLanguage, $targetLanguage)
    {
        $client = new TranslateClient([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
        ]);

        $result = $client->translateText([
            'SourceLanguageCode' => $sourceLanguage,
            'TargetLanguageCode' => $targetLanguage,
            'Text' => $sourceText,
        ]);
        return $result['TranslatedText'];
    }

    public static function autoTranslateToEnglish($sourceText)
    {
        $sourceLanguage = self::detectLanguage($sourceText);
        if ($sourceLanguage === 'en') {
            return $sourceText;
        } else {
            return self::translate($sourceText, $sourceLanguage, 'en');
        }
    }

    private static function detectLanguage($text)
    {
        $client = new ComprehendClient([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
        ]);
        $result = $client->detectDominantLanguage([
            'Text' => $text,
        ]);

        $languages = $result['Languages'];
        return $languages[0]['LanguageCode'];
    }
}
