<?php

namespace Tests;

use LibreTranslateLaravel\LibreTranslateAPI\LibreTranslate;
use PHPUnit\Framework\TestCase;

class LibreTranslateTest extends TestCase
{
    public function test_translate()
    {
        $api = new LibreTranslate('https://translate.argosopentech.com/', null, 'en', 'ar', 'silent');
        $arabic = $api->translate('Sample test');
        $this->assertSame('اختبار العينات', $arabic, 'Words should match!');
    }
}
