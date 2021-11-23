<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use LibreTranslateLaravel\LibreTranslateTranslation;

function is_target_lang($text)
{
    $target_lang = Config::get('libretranslate.target_lang_full');
    return (preg_match("/\p{$target_lang}/u", $text) > 0);
}

function is_html($text)
{
    return (preg_match("/<[^<]+>/", $text) != 0);
}

function __t($text) {

    if (App::getLocale() !== Config::get('libretranslate.target_lang') || is_target_lang($text) || is_html($text)) {
        return $text;
    }

    $cache = Cache::store('file');

    $slug = Str::slug($text, '_');
    return $cache->get('libretranslation.' . $slug, function () use($text, $cache, $slug) {

        $result = null;

        // Translation found in DB
        if ($translation = LibreTranslateTranslation::hasTranslation($text)) {
            if ($translation->translation_found) {
                $result = $translation->translated_text;
            } else {
                $result = $text;
            }
        } else {

            // Translation not found in DB
            $translator = App:: app(LibreTranslate::class);
            $translated = $translator->translate($text);
            if ($translated) {
                LibreTranslateTranslation::create([
                    'source_text' => $text,
                    'translated_text' => $translated,
                    'translation_found' => true,
                ]);
                $result = $translated;
            } else {
                LibreTranslateTranslation::create([
                    'source_text' => $text,
                    'translated_text' => null,
                    'translation_found' => false,
                ]);
                $result = $text;
            }
        }

        $cache->put('libretranslation.' . $slug, $result);
        return $result;
    });
}