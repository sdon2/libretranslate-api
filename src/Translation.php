<?php

namespace LibreTranslateLaravel;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $table = 'translations';

    protected $primaryKey = 'id';

    protected $fillable = [
    	'english_text',
    	'arabic_text',
        'translation_found',
    ];

    protected $casts = [
        'translation_found' => 'boolean',
    ];

    public function setEnglishTextAttribute($value)
    {
        $this->attributes['english_text'] = trim($value);
    }

    public static function hasTranslation($text)
    {
        return self::query()->where('english_text', trim($text))->first();
    }
}
