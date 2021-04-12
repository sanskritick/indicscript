<?php

namespace Sanskritick\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool isCommon($string)
 * @method static bool isArabic($string)
 * @method static bool isArmenian($string)
 * @method static bool isBengali($string)
 * @method static bool isBopomofo($string)
 * @method static bool isBraille($string)
 * @method static bool isBuhid($string)
 * @method static bool isCanadian_Aboriginal($string)
 * @method static bool isCherokee($string)
 * @method static bool isCyrillic($string)
 * @method static bool isDevanagari($string)
 * @method static bool isEthiopic($string)
 * @method static bool isGeorgian($string)
 * @method static bool isGreek($string)
 * @method static bool isGujarati($string)
 * @method static bool isHan($string)
 * @method static bool isHangul($string)
 * @method static bool isHanunoo($string)
 * @method static bool isHebrew($string)
 * @method static bool isHiragana($string)
 * @method static bool isInherited($string)
 * @method static bool isKannada($string)
 * @method static bool isKatakana($string)
 * @method static bool isKhmer($string)
 * @method static bool isLao($string)
 * @method static bool isLatin($string)
 * @method static bool isLimbu($string)
 * @method static bool isMalayalam($string)
 * @method static bool isMongolian($string)
 * @method static bool isMyanmar($string)
 * @method static bool isOgham($string)
 * @method static bool isOriya($string)
 * @method static bool isRunic($string)
 * @method static bool isSinhala($string)
 * @method static bool isSyriac($string)
 * @method static bool isTagalog($string)
 * @method static bool isTagbanwa($string)
 * @method static bool isTaiLe($string)
 * @method static bool isTamil($string)
 * @method static bool isTelugu($string)
 * @method static bool isThaana($string)
 * @method static bool isThai($string)
 * @method static bool isTibetan($string)
 * @method static bool isYi($string)
 * @method static bool isChinese($string)
 * @method static bool isJapanese($string)
 * @method bool isRomanScheme($name)
 * @method array addBrahmicScheme(string $name, array &$scheme)
 * @method array addRomanScheme(string $name, array &$scheme)
 * @method static string transliterate(string $data, string $from, string $to, $options = null)
 *
 * */
class IndicScript extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'indicscript';
    }
}
