<?php

namespace LibreTranslateLaravel;

use Illuminate\Database\Eloquent\Model;

class LibreTranslateTranslation extends Model
{
    protected $table = 'libretranslate_translations';

    protected $primaryKey = 'id';

    protected $fillable = [
    	'source_text',
    	'translated_text',
        'translation_found',
    ];

    protected $casts = [
        'translation_found' => 'boolean',
    ];

    public function setSourceTextAttribute($value)
    {
        $this->attributes['source_text'] = trim($value);
    }

    public static function hasTranslation($text)
    {
        return self::query()->where('source_text', trim($text))->first();
    }
}
