<?php

namespace OdooAPILaravel;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use LibreTranslateLaravel\LibreTranslateAPI\LibreTranslate;

class LibreTranslateServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/libretranslate.php' => config_path('libretranslate.php'),
        ]);
    }

    public function register()
    {
        $logFunction = function ($error) {
            Log::error('LibreTranslate: ' . $error);
        };

        $this->app->singleton(LibreTranslate::class, function () use ($logFunction) {
            $libretranslate = new LibreTranslate(Config::get('libretranslate.libretranslate_server'), Config::get('libretranslate.api_key'), Config::get('libretranslate.source_lang'), Config::get('libretranslate.target_lang'), Config::get('libretranslate.mode'));
            $libretranslate->setLogger($logFunction);
            return $libretranslate;
        });
    }
}
