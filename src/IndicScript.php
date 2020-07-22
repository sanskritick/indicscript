<?php

namespace Sanskritick\Script;

/**
 * IndicScript.
 *
 * IndicScript is a indic transliteration library.
 *
 * Released under the MIT and GPL Licenses.
 */
class IndicScript
{
    // Object cache.
    private array $cache = [];
    private array $schemes;

    const REGEX_COMMON              = '/\p{Common}/u';
    const REGEX_ARABIC              = '/\p{Arabic}/u';
    const REGEX_ARMENIAN            = '/\p{Armenian}/u';
    const REGEX_BENGALI             = '/\p{Bengali}/u';
    const REGEX_BOPOMOFO            = '/\p{Bopomofo}/u';
    const REGEX_BRAILLE             = '/\p{Braille}/u';
    const REGEX_BUHID               = '/\p{Buhid}/u';
    const REGEX_CANADIAN_ABORIGINAL = '/\p{Canadian_Aboriginal}/u';
    const REGEX_CHEROKEE            = '/\p{Cherokee}/u';
    const REGEX_CYRILLIC            = '/\p{Cyrillic}/u';
    const REGEX_DEVANAGARI          = '/\p{Devanagari}/u';
    const REGEX_ETHIOPIC            = '/\p{Ethiopic}/u';
    const REGEX_GEORGIAN            = '/\p{Georgian}/u';
    const REGEX_GREEK               = '/\p{Greek}/u';
    const REGEX_GUJARATI            = '/\p{Gujarati}/u';
    const REGEX_GURMUKHI            = '/\p{Gurmukhi}/u';
    const REGEX_HAN                 = '/\p{Han}/u';
    const REGEX_HANGUL              = '/\p{Hangul}/u';
    const REGEX_HANUNOO             = '/\p{Hanunoo}/u';
    const REGEX_HEBREW              = '/\p{Hebrew}/u';
    const REGEX_HIRAGANA            = '/\p{Hiragana}/u';
    const REGEX_INHERITED           = '/\p{Inherited}/u';
    const REGEX_KANNADA             = '/\p{Kannada}/u';
    const REGEX_KATAKANA            = '/\p{Katakana}/u';
    const REGEX_KHMER               = '/\p{Khmer}/u';
    const REGEX_LAO                 = '/\p{Lao}/u';
    const REGEX_LATIN               = '/\p{Latin}/u';
    const REGEX_LIMBU               = '/\p{Limbu}/u';
    const REGEX_MALAYALAM           = '/\p{Malayalam}/u';
    const REGEX_MONGOLIAN           = '/\p{Mongolian}/u';
    const REGEX_MYANMAR             = '/\p{Myanmar}/u';
    const REGEX_OGHAM               = '/\p{Ogham}/u';
    const REGEX_ORIYA               = '/\p{Oriya}/u';
    const REGEX_RUNIC               = '/\p{Runic}/u';
    const REGEX_SINHALA             = '/\p{Sinhala}/u';
    const REGEX_SYRIAC              = '/\p{Syriac}/u';
    const REGEX_TAGALOG             = '/\p{Tagalog}/u';
    const REGEX_TAGBANWA            = '/\p{Tagbanwa}/u';
    const REGEX_TAILE               = '/\p{TaiLe}/u';
    const REGEX_TAMIL               = '/\p{Tamil}/u';
    const REGEX_TELUGU              = '/\p{Telugu}/u';
    const REGEX_THAANA              = '/\p{Thaana}/u';
    const REGEX_THAI                = '/\p{Thai}/u';
    const REGEX_TIBETAN             = '/\p{Tibetan}/u';
    const REGEX_YI                  = '/\p{Yi}/u';

    public function __construct()
    {
        $this->schemes = IndicSchemes::getSchemes();
        $this->convertUnicodeConstants($this->schemes['devanagari']['zwj']);
        $this->convertUnicodeConstants($this->schemes['devanagari']['accent']);
        $this->setUpSchemes();
    }

    // Transliteration process option defaults.
    public array $defaults = [
        'skip_sgml' => false,
        'syncope' => false,
    ];

    // Set of names of Roman schemes.
    private array $romanSchemes = [];

    // Map of alternate encodings.
    private array $allAlternates = [
        'itrans' => [
            'A' => ['aa'],
            'I' => ['ii', 'ee'],
            'U' => ['uu', 'oo'],
            'RRi' => ['R^i'],
            'RRI' => ['R^I'],
            'LLi' => ['L^i'],
            'LLI' => ['L^I'],
            'M' => ['.m', '.n'],
            '~N' => ['N^'],
            'ch' => ['c'],
            'Ch' => ['C', 'chh'],
            '~n' => ['JN'],
            'v' => ['w'],
            'Sh' => ['S', 'shh'],
            'kSh' => ['kS', 'x'],
            'j~n' => ['GY', 'dny'],
            'OM' => ['AUM'],
            '\\_' => ['\\`'],
            '\\_H' => ['\\`H'],
            "\\'M" => ["\\'.m", "\\'.n"],
            '\\_M' => ['\\_.m', '\\_.n', '\\`M', '\\`.m', '\\`.n'],
            '.a' => ['~'],
            '|' => ['.'],
            '||' => ['..'],
            'z' => ['J'],
        ],
    ];

    /**
     * Work around the lack of Unicode escape sequence decoding in PHP strings.
     *
     * @param array $values array of Unicode character constants
     */
    private function convertUnicodeConstants(&$values)
    {
        $values = json_decode('["' . implode('","', $values) . '"]');
    }

    public static function isCommon($string)
    {
        return preg_match(self::REGEX_COMMON, $string) > 0;
    }

    public static function isArabic($string)
    {
        return preg_match(self::REGEX_ARABIC, $string) > 0;
    }

    public static function isArmenian($string)
    {
        return preg_match(self::REGEX_ARMENIAN, $string) > 0;
    }

    public static function isBengali($string)
    {
        return preg_match(self::REGEX_BENGALI, $string) > 0;
    }

    public static function isBopomofo($string)
    {
        return preg_match(self::REGEX_BOPOMOFO, $string) > 0;
    }

    public static function isBraille($string)
    {
        return preg_match(self::REGEX_BRAILLE, $string) > 0;
    }

    public static function isBuhid($string)
    {
        return preg_match(self::REGEX_BUHID, $string) > 0;
    }

    public static function isCanadian_Aboriginal($string)
    {
        return preg_match(self::REGEX_CANADIAN_ABORIGINAL, $string) > 0;
    }

    public static function isCherokee($string)
    {
        return preg_match(self::REGEX_CHEROKEE, $string) > 0;
    }

    public static function isCyrillic($string)
    {
        return preg_match(self::REGEX_CYRILLIC, $string) > 0;
    }

    public static function isDevanagari($string)
    {
        return preg_match(self::REGEX_DEVANAGARI, $string) > 0;
    }

    public static function isEthiopic($string)
    {
        return preg_match(self::REGEX_ETHIOPIC, $string) > 0;
    }

    public static function isGeorgian($string)
    {
        return preg_match(self::REGEX_GEORGIAN, $string) > 0;
    }

    public static function isGreek($string)
    {
        return preg_match(self::REGEX_GREEK, $string) > 0;
    }

    public static function isGujarati($string)
    {
        return preg_match(self::REGEX_GUJARATI, $string) > 0;
    }

    public static function isGurmukhi($string)
    {
        return preg_match(self::REGEX_GURMUKHI, $string) > 0;
    }

    public static function isHan($string)
    {
        return preg_match(self::REGEX_HAN, $string) > 0;
    }

    public static function isHangul($string)
    {
        return preg_match(self::REGEX_HANGUL, $string) > 0;
    }

    public static function isHanunoo($string)
    {
        return preg_match(self::REGEX_HANUNOO, $string) > 0;
    }

    public static function isHebrew($string)
    {
        return preg_match(self::REGEX_HEBREW, $string) > 0;
    }

    public static function isHiragana($string)
    {
        return preg_match(self::REGEX_HIRAGANA, $string) > 0;
    }

    public static function isInherited($string)
    {
        return preg_match(self::REGEX_INHERITED, $string) > 0;
    }

    public static function isKannada($string)
    {
        return preg_match(self::REGEX_KANNADA, $string) > 0;
    }

    public static function isKatakana($string)
    {
        return preg_match(self::REGEX_KATAKANA, $string) > 0;
    }

    public static function isKhmer($string)
    {
        return preg_match(self::REGEX_KHMER, $string) > 0;
    }

    public static function isLao($string)
    {
        return preg_match(self::REGEX_LAO, $string) > 0;
    }

    public static function isLatin($string)
    {
        return preg_match(self::REGEX_LATIN, $string) > 0;
    }

    public static function isLimbu($string)
    {
        return preg_match(self::REGEX_LIMBU, $string) > 0;
    }

    public static function isMalayalam($string)
    {
        return preg_match(self::REGEX_MALAYALAM, $string) > 0;
    }

    public static function isMongolian($string)
    {
        return preg_match(self::REGEX_MONGOLIAN, $string) > 0;
    }

    public static function isMyanmar($string)
    {
        return preg_match(self::REGEX_MYANMAR, $string) > 0;
    }

    public static function isOgham($string)
    {
        return preg_match(self::REGEX_OGHAM, $string) > 0;
    }

    public static function isOriya($string)
    {
        return preg_match(self::REGEX_ORIYA, $string) > 0;
    }

    public static function isRunic($string)
    {
        return preg_match(self::REGEX_RUNIC, $string) > 0;
    }

    public static function isSinhala($string)
    {
        return preg_match(self::REGEX_SINHALA, $string) > 0;
    }

    public static function isSyriac($string)
    {
        return preg_match(self::REGEX_SYRIAC, $string) > 0;
    }

    public static function isTagalog($string)
    {
        return preg_match(self::REGEX_TAGALOG, $string) > 0;
    }

    public static function isTagbanwa($string)
    {
        return preg_match(self::REGEX_TAGBANWA, $string) > 0;
    }

    public static function isTaiLe($string)
    {
        return preg_match(self::REGEX_TAILE, $string) > 0;
    }

    public static function isTamil($string)
    {
        return preg_match(self::REGEX_TAMIL, $string) > 0;
    }

    public static function isTelugu($string)
    {
        return preg_match(self::REGEX_TELUGU, $string) > 0;
    }

    public static function isThaana($string)
    {
        return preg_match(self::REGEX_THAANA, $string) > 0;
    }

    public static function isThai($string)
    {
        return preg_match(self::REGEX_THAI, $string) > 0;
    }

    public static function isTibetan($string)
    {
        return preg_match(self::REGEX_TIBETAN, $string) > 0;
    }

    public static function isYi($string)
    {
        return preg_match(self::REGEX_YI, $string) > 0;
    }

    /* --------------------------------------------------------
     * Proxies for the common person
     * ----------------------------------------------------- */
    public static function isChinese($string)
    {
        return self::isHan($string);
    }

    public static function isJapanese($string)
    {
        return self::isHiragana($string) || self::isKatakana($string);
    }

    /**
     * Check whether the given scheme encodes romanized Sanskrit.
     *
     * @param string $name the scheme name
     *
     * @return bool
     */
    public function isRomanScheme($name)
    {
        return isset($this->romanSchemes[$name]);
    }

    /**
     * Add a Brahmic scheme to IndicScript.
     *
     * Schemes are of two types: "Brahmic" and "roman". Brahmic consonants
     * have an inherent vowel sound, but roman consonants do not. This is the
     * main difference between these two types of scheme.
     *
     * A scheme definition is an array that maps a group name to a list of
     * characters. For illustration, see the "devanagari" scheme at the top of
     * this file.
     *
     * You can use whatever group names you like, but for the best results,
     * you should use the same group names that IndicScript does.
     *
     * @param string $name   the scheme name
     * @param array  $scheme the scheme data itself. This should be constructed
     *                       as described above.
     */
    public function addBrahmicScheme($name, &$scheme)
    {
        $this->schemes[$name] = $scheme;
    }

    /**
     * Add a roman scheme to IndicScript.
     *
     * See the comments on addBrahmicScheme. The "vowel_marks" field can be
     * omitted.
     *
     * @param string $name   the scheme name
     * @param array  $scheme the scheme data itself
     */
    public function addRomanScheme($name, &$scheme)
    {
        if (! isset($scheme['vowel_marks'])) {
            $scheme['vowel_marks'] = array_slice($scheme['vowels'], 1);
        }
        $this->schemes[$name]      = $scheme;
        $this->romanSchemes[$name] = true;
    }

    /**
     * Create a deep copy of an object, for certain kinds of objects.
     *
     * @param array $scheme the scheme to copy
     *
     * @return array the copy
     */
    private function cheapCopy(&$scheme)
    {
        $copy = [];
        foreach ($scheme as $key => $value) {
            // PHP assignment automatically copies an array $value.
            // @see http://us2.php.net/manual/en/language.types.array.php
            $copy[$key] = $value;
        }

        return $copy;
    }

    /**
     * Set up various schemes.
     */
    private function setUpSchemes()
    {
        // Set up roman schemes
        $kolkata                  = $this->cheapCopy($this->schemes['iast']);
        $kolkata['vowels']        = ['a', 'ā', 'i', 'ī', 'u', 'ū', 'ṛ', 'ṝ', 'ḷ', 'ḹ', 'e', 'ē', 'ai', 'o', 'ō', 'au'];
        $this->schemes['kolkata'] = &$kolkata;

        $schemeNames = ['iast', 'itrans', 'hk', 'kolkata', 'slp1', 'velthuis', 'wx'];
        // These schemes already belong to $schemes. But by adding
        // them again with `addRomanScheme`, we automatically build up
        // `romanSchemes` and define a `vowel_marks` field for each one.
        foreach ($schemeNames as $name) {
            $this->addRomanScheme($name, $this->schemes[$name]);
        }

        // ITRANS variant, which supports Dravidian short 'e' and 'o'.
        $itrans_dravidian                        = $this->cheapCopy($this->schemes['itrans']);
        $itrans_dravidian['vowels']              = ['a', 'A', 'i', 'I', 'u', 'U', 'Ri', 'RRI', 'LLi', 'LLi', 'e', 'E', 'ai', 'o', 'O', 'au'];
        $itrans_dravidian['vowel_marks']         = array_slice($itrans_dravidian['vowels'], 1);
        $this->allAlternates['itrans_dravidian'] = $this->allAlternates['itrans'];
        $this->addRomanScheme('itrans_dravidian', $itrans_dravidian);
    }

    /**
     * Create a map from every character in `from` to its partner in `to`.
     * Also, store any "marks" that `from` might have.
     *
     * @param string $from    input scheme
     * @param string $to      output scheme
     * @param array  $options scheme options
     *
     * @return array the map
     */
    private function makeMap($from, $to, &$options)
    {
        $consonants   = [];
        $fromScheme   = &$this->schemes[$from];
        $letters      = [];
        $tokenLengths = [];
        $marks        = [];
        $toScheme     = &$this->schemes[$to];

        if (isset($this->allAlternates[$from])) {
            $alternates = &$this->allAlternates[$from];
        } else {
            $alternates = [];
        }

        foreach ($fromScheme as $group => &$fromGroup) {
            if (! isset($toScheme[$group])) {
                continue;
            }
            $fromLength = count($fromGroup);
            $toGroup    = &$toScheme[$group];

            for ($i = 0; $i < $fromLength; ++$i) {
                $F = $fromGroup[$i];

                if ($F !== '') {
                    $T    = $toGroup[$i];
                    $alts = isset($alternates[$F]) ? $alternates[$F] : [];

                    $tokenLengths[] = mb_strlen($F, 'UTF-8');
                    foreach ($alts as $alt) {
                        $tokenLengths[] = mb_strlen($alt, 'UTF-8');
                    }

                    if ($group === 'vowel_marks' || $group === 'virama') {
                        $marks[$F] = $T;
                        foreach ($alts as $alt) {
                            $marks[$alt] = $T;
                        }
                    } else {
                        $letters[$F] = $T;
                        foreach ($alts as $alt) {
                            $letters[$alt] = $T;
                        }
                        if ($group === 'consonants' || $group === 'other') {
                            $consonants[$F] = $T;
                            foreach ($alts as $alt) {
                                $consonants[$alt] = $T;
                            }
                        }
                    }
                }
            }
        }

        return [
            'consonants'     => &$consonants,
            'fromRoman'      => $this->isRomanScheme($from),
            'letters'        => &$letters,
            'marks'          => &$marks,
            'maxTokenLength' => max($tokenLengths),
            'toRoman'        => $this->isRomanScheme($to),
            'virama'         => $toScheme['virama'][0],
        ];
    }

    /**
     * Transliterate from a romanized script.
     *
     * @param string $data    the string to transliterate
     * @param array  $map     map data generated from makeMap()
     * @param array  $options transliteration options
     *
     * @return string the finished string
     */
    private function transliterateRoman($data, &$map, &$options)
    {
        $buf            = [];
        $consonants     = &$map['consonants'];
        $hadConsonant   = false;
        $letters        = &$map['letters'];
        $marks          = &$map['marks'];
        $maxTokenLength = &$map['maxTokenLength'];
        $optSkipSGML    = $options['skip_sgml'];
        $optSyncope     = $options['syncope'];
        $tokenBuffer    = '';
        $toRoman        = &$map['toRoman'];
        $virama         = &$map['virama'];
        $dataChars      = preg_split('//u', $data, -1, PREG_SPLIT_NO_EMPTY);
        $dataLength     = count($dataChars);

        // Transliteration state. It's controlled by these values:
        // - `$skippingSGML`: are we in SGML?
        // - `$toggledTrans`: are we in a toggled region?
        //
        // We combine these values into a single variable `$skippingTrans`:
        //
        // `$skippingTrans` = $skippingSGML || $toggledTrans;
        //
        // If (and only if) this value is true, don't transliterate.
        $skippingSGML  = false;
        $skippingTrans = false;
        $toggledTrans  = false;

        for ($i = 0; ($i < $dataLength || $tokenBuffer); ++$i) {
            // Fill the token buffer, if possible.
            $difference = $maxTokenLength - mb_strlen($tokenBuffer, 'UTF-8');
            if ($difference > 0 && $i < $dataLength) {
                $tokenBuffer .= $dataChars[$i];
                if ($difference > 1) {
                    continue;
                }
            }

            // Match all token substrings to our map.
            for ($j = 0; $j < $maxTokenLength; ++$j) {
                $token = mb_substr($tokenBuffer, 0, $maxTokenLength - $j, 'UTF-8');

                if ($skippingSGML) {
                    $skippingSGML = ($token !== '>');
                } elseif ($token === '<') {
                    $skippingSGML = $optSkipSGML;
                } elseif ($token === '##') {
                    $toggledTrans = ! $toggledTrans;
                    $tokenBuffer  = mb_substr($tokenBuffer, 2, null, 'UTF-8');
                    break;
                }
                $skippingTrans = $skippingSGML || $toggledTrans;
                if (isset($letters[$token]) && ! $skippingTrans) {
                    if ($toRoman) {
                        $buf[] = $letters[$token];
                    } else {
                        // Handle the implicit vowel. Ignore 'a' and force
                        // vowels to appear as marks if we've just seen a
                        // consonant.
                        if ($hadConsonant) {
                            if (isset($marks[$token])) {
                                $buf[] = $marks[$token];
                            } elseif ($token !== 'a') {
                                $buf[] = $virama;
                                $buf[] = $letters[$token];
                            }
                        } else {
                            $buf[] = $letters[$token];
                        }
                        $hadConsonant = isset($consonants[$token]);
                    }
                    $tokenBuffer = mb_substr($tokenBuffer, $maxTokenLength - $j, null, 'UTF-8');
                    break;
                } elseif ($j === $maxTokenLength - 1) {
                    if ($hadConsonant) {
                        $hadConsonant = false;
                        if (! $optSyncope) {
                            $buf[] = $virama;
                        }
                    }
                    $buf[]       = $token;
                    $tokenBuffer = mb_substr($tokenBuffer, 1, null, 'UTF-8');
                    // 'break' is redundant here, "$j == ..." is true only on
                    // the last iteration.
                }
            }
        }
        if ($hadConsonant && ! $optSyncope) {
            $buf[] = $virama;
        }

        return implode('', $buf);
    }

    /**
     * Transliterate from a Brahmic script.
     *
     * @param string $data    the string to transliterate
     * @param array  $map     map data generated from makeMap()
     * @param array  $options transliteration options
     *
     * @return string the finished string
     */
    private function transliterateBrahmic($data, &$map, &$options)
    {
        $buf               = [];
        $consonants        = &$map['consonants'];
        $danglingHash      = false;
        $hadRomanConsonant = false;
        $letters           = &$map['letters'];
        $marks             = &$map['marks'];
        $toRoman           = &$map['toRoman'];
        $skippingTrans     = false;
        $dataChars         = preg_split('//u', $data, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($dataChars as $L) {
            // Toggle transliteration state
            if ($L === '#') {
                if ($danglingHash) {
                    $skippingTrans = ! $skippingTrans;
                    $danglingHash  = false;
                } else {
                    $danglingHash = true;
                }
                if ($hadRomanConsonant) {
                    $buf[]             = 'a';
                    $hadRomanConsonant = false;
                }
                continue;
            } elseif ($skippingTrans) {
                $buf[] = $L;
                continue;
            }

            if (isset($marks[$L])) {
                $buf[]             = $marks[$L];
                $hadRomanConsonant = false;
            } else {
                if ($danglingHash) {
                    $buf[]        = '#';
                    $danglingHash = false;
                }
                if ($hadRomanConsonant) {
                    $buf[]             = 'a';
                    $hadRomanConsonant = false;
                }

                // Push transliterated letter if possible. Otherwise, push
                // the letter itself.
                if (isset($letters[$L]) && $letters[$L] !== '') {
                    $buf[]             = $letters[$L];
                    $hadRomanConsonant = $toRoman && isset($consonants[$L]);
                } else {
                    $buf[] = $L;
                }
            }
        }
        if ($hadRomanConsonant) {
            $buf[] = 'a';
        }

        return implode('', $buf);
    }

    /**
     * Transliterate from one script to another.
     *
     * @param string $data    the string to transliterate
     * @param string $from    the source script
     * @param string $to      the destination script
     * @param array  $options transliteration options
     *
     * @return string the finished string
     */
    public function transliterate($data, $from, $to, $options = null)
    {
        $options       = isset($options) ? $options : [];
        $cachedOptions = isset($this->cache['options']) ? $this->cache['options'] : [];
        $hasPriorState = (isset($this->cache['from']) && $this->cache['from'] === $from && isset($this->cache['to']) && $this->cache['to'] === $to);

        // Here we simultaneously build up an `options` object and compare
        // these options to the options from the last run.
        foreach ($this->defaults as $key => $value) {
            if (isset($options[$key])) {
                $value = $options[$key];
            }
            $options[$key] = $value;

            // This comparison method is not generalizable, but since these
            // objects are associative arrays with identical keys and with
            // values of known type, it works fine here.
            if (! isset($cachedOptions[$key]) || $value !== $cachedOptions[$key]) {
                $hasPriorState = false;
            }
        }

        if ($hasPriorState) {
            $map = $this->cache['map'];
        } else {
            $map         = $this->makeMap($from, $to, $options);
            $this->cache = [
                'from'    => $from,
                'map'     => &$map,
                'options' => $options,
                'to'      => $to,
            ];
        }

        // Easy way out for "{\m+}", "\", and ".h".
        if ($from === 'itrans') {
            $data = preg_replace("/\{\\\m\+\}/u", '.h.N', $data);
            $data = preg_replace("/\.h/u", '', $data);
            $data = preg_replace("/\\\([^'`_]|$)/u", '##$1##', $data);
        }

        if ($map['fromRoman']) {
            return $this->transliterateRoman($data, $map, $options);
        } else {
            return $this->transliterateBrahmic($data, $map, $options);
        }
    }
}
