<?php

return [

    /*
    |--------------------------------------------------------------------------
    | General TTS Settings
    |--------------------------------------------------------------------------
    */

    'enable' => [
        'aws' => env('CONFIG_ENABLE_AWS_AUDIO'),
        'aws_live' => env('CONFIG_ENABLE_AWS_AUDIO_LIVE'),
        'gcp' => env('CONFIG_ENABLE_GCP_AUDIO'),
    ],

    'language' => [
        'file' => env('CONFIG_DEFAULT_LANGUAGE_FILE'),
        'live' => env('CONFIG_DEFAULT_LANGUAGE_LIVE')
    ],

    'file_format' => env('CONFIG_FILE_FORMAT'),

    'max_size_limit' => env('CONFIG_MAX_SIZE_LIMIT', 10),

    'max_length_limit_file' => env('CONFIG_MAX_LENGTH_LIMIT_FILE', 5),

    'max_length_limit_live' => env('CONFIG_MAX_LENGTH_LIMIT_LIVE', 5),

    'max_length_limit_file_none' => env('CONFIG_MAX_LENGTH_LIMIT_FILE_NONE', 5),

    'max_length_limit_live_none' => env('CONFIG_MAX_LENGTH_LIMIT_LIVE_NONE', 5),

    'free_minutes' => env('CONFIG_FREE_MINUTES', 5),

    'vendor_logos' => env('CONFIG_VENDOR_LOGOS', 'show'),

    'speaker_identification' => env('CONFIG_SPEAKER_IDENTIFICATION'),

    'live_transcription_text_area' => env('CONFIG_LIVE_TRANSCRIPTION_TEXT_AREA'),

];
