<?php

return [


    'libretranslate_server' => env('LIBRETRANSLATE_SERVER_URI'),

    'api_key' => env('LIBRETRANSLATE_API_KEY'),

    'source_lang' => env('LIBRETRANSLATE_SOURCE_LANG'),

    'target_lang' => env('LIBRETRANSLATE_TRAGET_LANG'),

    'target_lang_full' => env('LIBRETRANSLATE_TARGET_LANG_FULL'),

    // 'silent' mode disbles throwing exceptions and logs errors
    // 'normal' mode enables throwing exceptions
    'mode' => 'silent',

];