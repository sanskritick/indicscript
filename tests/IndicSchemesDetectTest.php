<?php

namespace Sanskritick;

use PHPUnit\Framework\TestCase;
use Sanskritick\Script\IndicScript;

class IndicSchemesDetectTest extends TestCase
{
    /** @test */
    public function can_detect_thai()
    {
        $this->assertTrue(IndicScript::isThai('สวัสดีชาวโลกและยินดีต้อนรับแพ็กเกจนี้'));
        $this->assertFalse(IndicScript::isThai('Hello world, and welcome this package.'));
    }

    /** @test */
    public function can_detect_chinese()
    {
        $this->assertTrue(IndicScript::isChinese('你好世界，歡迎這個包。'));
        $this->assertTrue(IndicScript::isChinese('你好世界，欢迎这个包。'));
        $this->assertFalse(IndicScript::isChinese('Hello world, and welcome this package.'));
    }

    /** @test */
    public function can_detect_han()
    {
        $this->assertTrue(IndicScript::isHan('你好世界，歡迎這個包。'));
        $this->assertTrue(IndicScript::isHan('你好世界，欢迎这个包。'));
        $this->assertFalse(IndicScript::isHan('Hello world, and welcome this package.'));
    }

    /** @test */
    public function can_detect_japanese()
    {
        $this->assertTrue(IndicScript::isJapanese('こんにちは、このパッケージを歓迎します。'));
        $this->assertFalse(IndicScript::isJapanese('Hello world, and welcome this package.'));
    }

    /** @test */
    public function can_detect_common()
    {
        $this->assertTrue(IndicScript::isCommon('Hello world, and welcome this package.'));
        $this->assertTrue(IndicScript::isCommon('こんにちは、このパッケージを歓迎します'));
        $this->assertFalse(IndicScript::isCommon('ψ'));
    }

    /** @test */
    public function can_detect_latin()
    {
        $this->assertTrue(IndicScript::isLatin('Salve mundi, et receperint hac sarcina.'));
        $this->assertTrue(IndicScript::isLatin('Hello world, and welcome this package.'));
        $this->assertFalse(IndicScript::isLatin('こんにちは、このパッケージを歓迎します'));
    }

    /** @test */
    public function can_detect_arabic()
    {
        $this->assertTrue(IndicScript::isArabic('مرحبا العالم، ونرحب بهذه الحزمة.'));
        $this->assertFalse(IndicScript::isArabic('Hello world, and welcome this package.'));
    }

    /** @test */
    public function can_detect_georgian()
    {
        $this->assertTrue(IndicScript::isGeorgian('გამარჯობა მსოფლიოში და მივესალმები ამ პაკეტს.'));
        $this->assertFalse(IndicScript::isGeorgian('Hello world, and welcome this package.'));
    }

    /** @test */
    public function can_detect_canadian_aboriginal()
    {
        $this->assertTrue(IndicScript::isCanadian_Aboriginal('ᓭ	ᓭ	ᓯ	ᓯ	ᓱ	ᓱ	ᓴ	ᓴ ᐯ	ᐯ	ᐱ	ᐱ	ᐳ	ᐳ	ᐸ	ᐸ'));
        $this->assertFalse(IndicScript::isCanadian_Aboriginal('Hello world, and welcome this package.'));
    }

    /** @test */
    public function can_detect_tibetan()
    {
        $this->assertTrue(IndicScript::isTibetan('ཀཁཆཇའ'));
        $this->assertFalse(IndicScript::isTibetan('Hello world, and welcome this package.'));
    }

    /** @test */
    public function can_detect_hangul()
    {
        $this->assertTrue(IndicScript::isHangul('ㄱ	ㅋ	ㄲㅅ		ㅆㅈ	ㅊ	ㅉㄷ	ㅌ	ㄸㅂ	ㅍ	ㅃ'));
        $this->assertFalse(IndicScript::isHangul('Hello world, and welcome this package.'));
    }

    /** @test */
    public function can_detect_braille()
    {
        $this->assertTrue(IndicScript::isBraille('⠡⠣⠩⠹⠱⠫⠻⠳⠪⠺'));
        $this->assertFalse(IndicScript::isBraille('Hello world, and welcome this package.'));
    }

    /** @test */
    public function can_detect_bopomofo()
    {
        $this->assertTrue(IndicScript::isBopomofo('瓶	ㄆㄧㄥˊ子	ㄗ˙or	ㄆㄧㄥˊ	ㄗ˙瓶	子'));
        $this->assertFalse(IndicScript::isBopomofo('Hello world, and welcome this package.'));
    }

    /** @test */
    public function can_detect_bengali()
    {
        $this->assertTrue(IndicScript::isBengali('জ্জ ǰǰô জ্ঞ'));
        $this->assertFalse(IndicScript::isBengali('Hello world, and welcome this package.'));
    }

    /** @test */
    public function can_detect_buhid()
    {
        $this->assertTrue(IndicScript::isBuhid('ᝃ	ᝄ	ᝅ	ᝆ	ᝇ	ᝈ	ᝉ	ᝊ	ᝋ	ᝌ	ᝍ	ᝎ	ᝏ	ᝐ	ᝑ'));
        $this->assertFalse(IndicScript::isBuhid('Hello world, and welcome this package.'));
    }

    /** @test */
    public function can_detect_devanagari()
    {
        $this->assertTrue(IndicScript::isDevanagari('सदाऽऽत्मा'));
        $this->assertFalse(IndicScript::isDevanagari('Hello world, and welcome this package.'));
    }

    /** @test */
    public function can_detect_armenian()
    {
        $this->assertTrue(IndicScript::isArmenian('բենգիմžē'));
        $this->assertFalse(IndicScript::isArmenian('Hello world, and welcome this package.'));
    }

    /** @test */
    public function can_detect_hebrew()
    {
        $this->assertTrue(IndicScript::isHebrew('א ב ג ד ה ו ז ח ט י'));
        $this->assertFalse(IndicScript::isHebrew('Hello world, and welcome this package.'));
    }

    /** @test */
    public function can_detect_mongolian()
    {
        $this->assertTrue(IndicScript::isMongolian('ᠪᠣᠯᠠᠢ᠃'));
        $this->assertFalse(IndicScript::isMongolian('Hello world, and welcome this package.'));
    }
}
