<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use LibreTranslateLaravel\Translation;

function is_arabic($text)
{
    return (preg_match("/\p{Arabic}/u", $text) > 0);
}

function is_html($text)
{
    return (preg_match("/<[^<]+>/", $text) != 0);
}

function __t($text) {

    if (App::getLocale() !== 'ar' || is_arabic($text) || is_html($text)) {
        return $text;
    }

    $cache = Cache::store('file');

    $slug = Str::slug($text, '_');
    return $cache->get('translation.' . $slug, function () use($text, $cache, $slug) {

        $result = null;

        // Translation found in DB
        if ($translation = Translation::hasTranslation($text)) {
            if ($translation->translation_found) {
                $result = $translation->arabic_text;
            } else {
                $result = $text;
            }
        } else {

            // Translation not found in DB
            $translator = App:: app(LibreTranslate::class);
            $translated = $translator->translate($text);
            if ($translated) {
                Translation::create([
                    'english_text' => $text,
                    'arabic_text' => $translated,
                    'translation_found' => true,
                ]);
                $result = $translated;
            } else {
                Translation::create([
                    'english_text' => $text,
                    'arabic_text' => null,
                    'translation_found' => false,
                ]);
                $result = $text;
            }
        }

        $cache->put('translation.' . $slug, $result);
        return $result;
    });
}