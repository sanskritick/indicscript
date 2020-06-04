<?php

namespace Sanskritick;

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
    private $cache = [];

    const REGEX_DEVANAGARI = '/\p{Devanagari}/u';
    const REGEX_GUJARATI   = '/\p{Gujarati}/u';
    const REGEX_GURMUKHI   = '/\p{Gurmukhi}/u';
    const REGEX_KANNADA    = '/\p{Kannada}/u';
    const REGEX_MALAYALAM  = '/\p{Malayalam}/u';
    const REGEX_ORIYA      = '/\p{Oriya}/u';
    const REGEX_TAMIL      = '/\p{Tamil}/u';
    const REGEX_TELUGU     = '/\p{Telugu}/u';
    const REGEX_BENGALI    = '/\p{Bengali}/u';
    const REGEX_ARABIC     = '/\p{Arabic}/u';

    public function __construct()
    {
        $this->convertUnicodeConstants($this->schemes['devanagari']['zwj']);
        $this->convertUnicodeConstants($this->schemes['devanagari']['accent']);
        $this->setUpSchemes();
    }

    // Transliteration process option defaults.
    public $defaults = [
        'skip_sgml' => false,
        'syncope'   => false,
    ];

    /* Schemes
     * =======
     * Schemes are of two kinds: "Brahmic" and "roman." "Brahmic" schemes
     * describe abugida scripts found in India. "Roman" schemes describe
     * manufactured alphabets that are meant to describe or encode Brahmi
     * scripts. Abugidas and alphabets are processed by separate algorithms
     * because of the unique difficulties involved with each.
     *
     * Brahmic consonants are stated without a virama. Roman consonants are
     * stated without the vowel 'a'.
     *
     * (Since "abugida" is not a well-known term, IndicScript uses "Brahmic"
     * and "roman" for clarity.)
     */
    public $schemes = [
        /* Bengali
         * -------
         * 'va' and 'ba' are both rendered as ব.
         */
        'bengali' => [
            'vowels'      => ['অ', 'আ', 'ই', 'ঈ', 'উ', 'ঊ', 'ঋ', 'ৠ', 'ঌ', 'ৡ', '', 'এ', 'ঐ', '', 'ও', 'ঔ'],
            'vowel_marks' => ['া', 'ি', 'ী', 'ু', 'ূ', 'ৃ', 'ৄ', 'ৢ', 'ৣ', '', 'ে', 'ৈ', '', 'ো', 'ৌ'],
            'other_marks' => ['ং', 'ঃ', 'ঁ'],
            'virama'      => ['্'],
            'consonants'  => ['ক', 'খ', 'গ', 'ঘ', 'ঙ', 'চ', 'ছ', 'জ', 'ঝ', 'ঞ', 'ট', 'ঠ', 'ড', 'ঢ', 'ণ', 'ত', 'থ', 'দ', 'ধ', 'ন', 'প', 'ফ', 'ব', 'ভ', 'ম', 'য', 'র', 'ল', 'ব', 'শ', 'ষ', 'স', 'হ', 'ळ', 'ক্ষ', 'জ্ঞ'],
            'symbols'     => ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯', 'ॐ', 'ঽ', '।', '॥'],
            'other'       => ['', '', '', '', 'ড', 'ঢ', '', 'য', ''],
        ],

        /* Devanagari
         * ----------
         * The most comprehensive and unambiguous Brahmic script listed.
         */
        'devanagari' => [
            // "Independent" forms of the vowels. These are used whenever the
            // vowel does not immediately follow a consonant.
            'vowels' => ['अ', 'आ', 'इ', 'ई', 'उ', 'ऊ', 'ऋ', 'ॠ', 'ऌ', 'ॡ', 'ऎ', 'ए', 'ऐ', 'ऒ', 'ओ', 'औ'],

            // "Dependent" forms of the vowels. These are used whenever the
            // vowel immediately follows a consonant. If a letter is not
            // listed in `vowels`, it should not be listed here.
            'vowel_marks' => ['ा', 'ि', 'ी', 'ु', 'ू', 'ृ', 'ॄ', 'ॢ', 'ॣ', 'ॆ', 'े', 'ै', 'ॊ', 'ो', 'ौ'],

            // Miscellaneous marks, all of which are used in Sanskrit.
            'other_marks' => ['ं', 'ः', 'ँ'],

            // In syllabic scripts like Devanagari, consonants have an inherent
            // vowel that must be suppressed explicitly. We do so by putting a
            // virama after the consonant.
            'virama' => ['्'],

            // Various Sanskrit consonants and consonant clusters. Every token
            // here has an explicit vowel. Thus "क" is "ka" instead of "k".
            'consonants' => ['क', 'ख', 'ग', 'घ', 'ङ', 'च', 'छ', 'ज', 'झ', 'ञ', 'ट', 'ठ', 'ड', 'ढ', 'ण', 'त', 'थ', 'द', 'ध', 'न', 'प', 'फ', 'ब', 'भ', 'म', 'य', 'र', 'ल', 'व', 'श', 'ष', 'स', 'ह', 'ळ', 'क्ष', 'ज्ञ'],

            // Numbers and punctuation
            'symbols' => ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९', 'ॐ', 'ऽ', '।', '॥'],

            // Zero-width joiner. This is used to separate a consonant cluster
            // and avoid a complex ligature.
            'zwj' => ["\u200D"],

            // Dummy consonant. This is used in ITRANS to prevert certain types
            // of parser ambiguity. Thus "barau" -> बरौ but "bara_u" -> बरउ.
            'skip' => [''],

            // Vedic accent. Udatta and anudatta.
            'accent' => ["\u0951", "\u0952"],

            // Accent combined with anusvara and and visarga. For compatibility
            // with ITRANS, which allows the reverse of these four.
            'combo_accent' => ['ः॑', 'ः॒', 'ं॑', 'ं॒'],

            'candra' => ['ॅ'],

            // Non-Sanskrit consonants
            'other' => ['क़', 'ख़', 'ग़', 'ज़', 'ड़', 'ढ़', 'फ़', 'य़', 'ऱ'],
        ],

        /* Gujarati
         * --------
         * Sanskrit-complete.
         */
        'gujarati' => [
            'vowels'      => ['અ', 'આ', 'ઇ', 'ઈ', 'ઉ', 'ઊ', 'ઋ', 'ૠ', 'ઌ', 'ૡ', '', 'એ', 'ઐ', '', 'ઓ', 'ઔ'],
            'vowel_marks' => ['ા', 'િ', 'ી', 'ુ', 'ૂ', 'ૃ', 'ૄ', 'ૢ', 'ૣ', '', 'ે', 'ૈ', '', 'ો', 'ૌ'],
            'other_marks' => ['ં', 'ઃ', 'ઁ'],
            'virama'      => ['્'],
            'consonants'  => ['ક', 'ખ', 'ગ', 'ઘ', 'ઙ', 'ચ', 'છ', 'જ', 'ઝ', 'ઞ', 'ટ', 'ઠ', 'ડ', 'ઢ', 'ણ', 'ત', 'થ', 'દ', 'ધ', 'ન', 'પ', 'ફ', 'બ', 'ભ', 'મ', 'ય', 'ર', 'લ', 'વ', 'શ', 'ષ', 'સ', 'હ', 'ળ', 'ક્ષ', 'જ્ઞ'],
            'symbols'     => ['૦', '૧', '૨', '૩', '૪', '૫', '૬', '૭', '૮', '૯', 'ૐ', 'ઽ', '૤', '૥'],
            'candra'      => ['ૅ'],
        ],

        /* Gurmukhi
         * --------
         * Missing R/RR/lR/lRR
         */
        'gurmukhi' => [
            'vowels'      => ['ਅ', 'ਆ', 'ਇ', 'ਈ', 'ਉ', 'ਊ', '', '', '', '', '', 'ਏ', 'ਐ', '', 'ਓ', 'ਔ'],
            'vowel_marks' => ['ਾ', 'ਿ', 'ੀ', 'ੁ', 'ੂ', '', '', '', '', '', 'ੇ', 'ੈ', '', 'ੋ', 'ੌ'],
            'other_marks' => ['ਂ', 'ਃ', 'ਁ'],
            'virama'      => ['੍'],
            'consonants'  => ['ਕ', 'ਖ', 'ਗ', 'ਘ', 'ਙ', 'ਚ', 'ਛ', 'ਜ', 'ਝ', 'ਞ', 'ਟ', 'ਠ', 'ਡ', 'ਢ', 'ਣ', 'ਤ', 'ਥ', 'ਦ', 'ਧ', 'ਨ', 'ਪ', 'ਫ', 'ਬ', 'ਭ', 'ਮ', 'ਯ', 'ਰ', 'ਲ', 'ਵ', 'ਸ਼', 'ਸ਼', 'ਸ', 'ਹ', 'ਲ਼', 'ਕ੍ਸ਼', 'ਜ੍ਞ'],
            'symbols'     => ['੦', '੧', '੨', '੩', '੪', '੫', '੬', '੭', '੮', '੯', 'ॐ', 'ऽ', '।', '॥'],
            'other'       => ['', 'ਖ', 'ਗ', 'ਜ', 'ਡ', '', 'ਫ', '', ''],
        ],

        /* Kannada
         * -------
         * Sanskrit-complete.
         */
        'kannada' => [
            'vowels'      => ['ಅ', 'ಆ', 'ಇ', 'ಈ', 'ಉ', 'ಊ', 'ಋ', 'ೠ', 'ಌ', 'ೡ', 'ಎ', 'ಏ', 'ಐ', 'ಒ', 'ಓ', 'ಔ'],
            'vowel_marks' => ['ಾ', 'ಿ', 'ೀ', 'ು', 'ೂ', 'ೃ', 'ೄ', 'ೢ', 'ೣ', 'ೆ', 'ೇ', 'ೈ', 'ೊ', 'ೋ', 'ೌ'],
            'other_marks' => ['ಂ', 'ಃ', 'ँ'],
            'virama'      => ['್'],
            'consonants'  => ['ಕ', 'ಖ', 'ಗ', 'ಘ', 'ಙ', 'ಚ', 'ಛ', 'ಜ', 'ಝ', 'ಞ', 'ಟ', 'ಠ', 'ಡ', 'ಢ', 'ಣ', 'ತ', 'ಥ', 'ದ', 'ಧ', 'ನ', 'ಪ', 'ಫ', 'ಬ', 'ಭ', 'ಮ', 'ಯ', 'ರ', 'ಲ', 'ವ', 'ಶ', 'ಷ', 'ಸ', 'ಹ', 'ಳ', 'ಕ್ಷ', 'ಜ್ಞ'],
            'symbols'     => ['೦', '೧', '೨', '೩', '೪', '೫', '೬', '೭', '೮', '೯', 'ಓಂ', 'ಽ', '।', '॥'],
            'other'       => ['', '', '', '', '', '', 'ಫ', '', 'ಱ'],
        ],

        /* Malayalam
         * ---------
         * Sanskrit-complete.
         */
        'malayalam' => [
            'vowels'      => ['അ', 'ആ', 'ഇ', 'ഈ', 'ഉ', 'ഊ', 'ഋ', 'ൠ', 'ഌ', 'ൡ', 'എ', 'ഏ', 'ഐ', 'ഒ', 'ഓ', 'ഔ'],
            'vowel_marks' => ['ാ', 'ി', 'ീ', 'ു', 'ൂ', 'ൃ', 'ൄ', 'ൢ', 'ൣ', 'െ', 'േ', 'ൈ', 'ൊ', 'ോ', 'ൌ'],
            'other_marks' => ['ം', 'ഃ', 'ँ'],
            'virama'      => ['്'],
            'consonants'  => ['ക', 'ഖ', 'ഗ', 'ഘ', 'ങ', 'ച', 'ഛ', 'ജ', 'ഝ', 'ഞ', 'ട', 'ഠ', 'ഡ', 'ഢ', 'ണ', 'ത', 'ഥ', 'ദ', 'ധ', 'ന', 'പ', 'ഫ', 'ബ', 'ഭ', 'മ', 'യ', 'ര', 'ല', 'വ', 'ശ', 'ഷ', 'സ', 'ഹ', 'ള', 'ക്ഷ', 'ജ്ഞ'],
            'symbols'     => ['൦', '൧', '൨', '൩', '൪', '൫', '൬', '൭', '൮', '൯', 'ഓം', 'ഽ', '।', '॥'],
            'other'       => ['', '', '', '', '', '', '', '', 'റ'],
        ],

        /* Oriya
         * -----
         * Sanskrit-complete.
         */
        'oriya' => [
            'vowels'      => ['ଅ', 'ଆ', 'ଇ', 'ଈ', 'ଉ', 'ଊ', 'ଋ', 'ୠ', 'ଌ', 'ୡ', '', 'ଏ', 'ଐ', '', 'ଓ', 'ଔ'],
            'vowel_marks' => ['ା', 'ି', 'ୀ', 'ୁ', 'ୂ', 'ୃ', 'ୄ', 'ୢ', 'ୣ', '', 'େ', 'ୈ', '', 'ୋ', 'ୌ'],
            'other_marks' => ['ଂ', 'ଃ', 'ଁ'],
            'virama'      => ['୍'],
            'consonants'  => ['କ', 'ଖ', 'ଗ', 'ଘ', 'ଙ', 'ଚ', 'ଛ', 'ଜ', 'ଝ', 'ଞ', 'ଟ', 'ଠ', 'ଡ', 'ଢ', 'ଣ', 'ତ', 'ଥ', 'ଦ', 'ଧ', 'ନ', 'ପ', 'ଫ', 'ବ', 'ଭ', 'ମ', 'ଯ', 'ର', 'ଲ', 'ଵ', 'ଶ', 'ଷ', 'ସ', 'ହ', 'ଳ', 'କ୍ଷ', 'ଜ୍ଞ'],
            'symbols'     => ['୦', '୧', '୨', '୩', '୪', '୫', '୬', '୭', '୮', '୯', 'ଓଂ', 'ଽ', '।', '॥'],
            'other'       => ['', '', '', '', 'ଡ', 'ଢ', '', 'ଯ', ''],
        ],

        /* Tamil
         * -----
         * Missing R/RR/lR/lRR vowel marks and voice/aspiration distinctions.
         * The most incomplete of the Sanskrit schemes here.
         */
        'tamil' => [
            'vowels'      => ['அ', 'ஆ', 'இ', 'ஈ', 'உ', 'ஊ', '', '', '', '', 'எ', 'ஏ', 'ஐ', 'ஒ', 'ஓ', 'ஔ'],
            'vowel_marks' => ['ா', 'ி', 'ீ', 'ு', 'ூ', '', '', '', '', 'ெ', 'ே', 'ை', 'ொ', 'ோ', 'ௌ'],
            'other_marks' => ['ஂ', 'ஃ', ''],
            'virama'      => ['்'],
            'consonants'  => ['க', 'க', 'க', 'க', 'ங', 'ச', 'ச', 'ஜ', 'ச', 'ஞ', 'ட', 'ட', 'ட', 'ட', 'ண', 'த', 'த', 'த', 'த', 'ந', 'ப', 'ப', 'ப', 'ப', 'ம', 'ய', 'ர', 'ல', 'வ', 'ஶ', 'ஷ', 'ஸ', 'ஹ', 'ள', 'க்ஷ', 'ஜ்ஞ'],
            'symbols'     => ['௦', '௧', '௨', '௩', '௪', '௫', '௬', '௭', '௮', '௯', 'ௐ', 'ऽ', '।', '॥'],
            'other'       => ['', '', '', '', '', '', '', '', 'ற'],
        ],

        /* Telugu
         * ------
         * Sanskrit-complete.
         */
        'telugu' => [
            'vowels'      => ['అ', 'ఆ', 'ఇ', 'ఈ', 'ఉ', 'ఊ', 'ఋ', 'ౠ', 'ఌ', 'ౡ', 'ఎ', 'ఏ', 'ఐ', 'ఒ', 'ఓ', 'ఔ'],
            'vowel_marks' => ['ా', 'ి', 'ీ', 'ు', 'ూ', 'ృ', 'ౄ', 'ౢ', 'ౣ', 'ె', 'ే', 'ై', 'ొ', 'ో', 'ౌ'],
            'other_marks' => ['ం', 'ః', 'ఁ'],
            'virama'      => ['్'],
            'consonants'  => ['క', 'ఖ', 'గ', 'ఘ', 'ఙ', 'చ', 'ఛ', 'జ', 'ఝ', 'ఞ', 'ట', 'ఠ', 'డ', 'ఢ', 'ణ', 'త', 'థ', 'ద', 'ధ', 'న', 'ప', 'ఫ', 'బ', 'భ', 'మ', 'య', 'ర', 'ల', 'వ', 'శ', 'ష', 'స', 'హ', 'ళ', 'క్ష', 'జ్ఞ'],
            'symbols'     => ['౦', '౧', '౨', '౩', '౪', '౫', '౬', '౭', '౮', '౯', 'ఓం', 'ఽ', '।', '॥'],
            'other'       => ['', '', '', '', '', '', '', '', 'ఱ'],
        ],

        /* International Alphabet of Sanskrit Transliteration
         * --------------------------------------------------
         * The most "professional" Sanskrit romanization scheme.
         */
        'iast' => [
            'vowels'      => ['a', 'ā', 'i', 'ī', 'u', 'ū', 'ṛ', 'ṝ', 'ḷ', 'ḹ', '', 'e', 'ai', '', 'o', 'au'],
            'other_marks' => ['ṃ', 'ḥ', '~'],
            'virama'      => [''],
            'consonants'  => ['k', 'kh', 'g', 'gh', 'ṅ', 'c', 'ch', 'j', 'jh', 'ñ', 'ṭ', 'ṭh', 'ḍ', 'ḍh', 'ṇ', 't', 'th', 'd', 'dh', 'n', 'p', 'ph', 'b', 'bh', 'm', 'y', 'r', 'l', 'v', 'ś', 'ṣ', 's', 'h', 'ḻ', 'kṣ', 'jñ'],
            'symbols'     => ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'oṃ', "'", '।', '॥'],
        ],

        /* ITRANS
         * ------
         * One of the first romanization schemes -- and one of the most
         * complicated. For alternate forms, see the "allAlternates" variable
         * below.
         *
         * '_' is a "null" letter, which allows adjacent vowels.
         */
        'itrans' => [
            'vowels'       => ['a', 'A', 'i', 'I', 'u', 'U', 'RRi', 'RRI', 'LLi', 'LLI', '', 'e', 'ai', '', 'o', 'au'],
            'other_marks'  => ['M', 'H', '.N'],
            'virama'       => [''],
            'consonants'   => ['k', 'kh', 'g', 'gh', '~N', 'ch', 'Ch', 'j', 'jh', '~n', 'T', 'Th', 'D', 'Dh', 'N', 't', 'th', 'd', 'dh', 'n', 'p', 'ph', 'b', 'bh', 'm', 'y', 'r', 'l', 'v', 'sh', 'Sh', 's', 'h', 'L', 'kSh', 'j~n'],
            'symbols'      => ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'OM', '.a', '|', '||'],
            'candra'       => ['.c'],
            'zwj'          => ['{}'],
            'skip'         => '_',
            'accent'       => ["\\'", '\\_'],
            'combo_accent' => ["\\'H", '\\_H', "\\'M", '\\_M'],
            'other'        => ['q', 'K', 'G', 'z', '.D', '.Dh', 'f', 'Y', 'R'],
        ],

        /* Harvard-Kyoto
         * -------------
         * A simple 1:1 mapping.
         */
        'hk' => [
            'vowels'      => ['a', 'A', 'i', 'I', 'u', 'U', 'R', 'RR', 'lR', 'lRR', '', 'e', 'ai', '', 'o', 'au'],
            'other_marks' => ['M', 'H', '~'],
            'virama'      => [''],
            'consonants'  => ['k', 'kh', 'g', 'gh', 'G', 'c', 'ch', 'j', 'jh', 'J', 'T', 'Th', 'D', 'Dh', 'N', 't', 'th', 'd', 'dh', 'n', 'p', 'ph', 'b', 'bh', 'm', 'y', 'r', 'l', 'v', 'z', 'S', 's', 'h', 'L', 'kS', 'jJ'],
            'symbols'     => ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'OM', "'", '|', '||'],
        ],

        /* National Library at Kolkata
         * ---------------------------
         * Apart from using "ē" and "ō" instead of "e" and "o", this scheme is
         * identical to IAST. ṝ, ḷ, and ḹ are not part of the scheme proper.
         *
         * This is defined further below.
         */

        /* Sanskrit Library Phonetic Basic
         * -------------------------------
         * With one ASCII letter per phoneme, this is the tersest transliteration
         * scheme in use today and is especially suited to computer processing.
         */
        'slp1' => [
            'vowels'      => ['a', 'A', 'i', 'I', 'u', 'U', 'f', 'F', 'x', 'X', '', 'e', 'E', '', 'o', 'O'],
            'other_marks' => ['M', 'H', '~'],
            'virama'      => [''],
            'consonants'  => ['k', 'K', 'g', 'G', 'N', 'c', 'C', 'j', 'J', 'Y', 'w', 'W', 'q', 'Q', 'R', 't', 'T', 'd', 'D', 'n', 'p', 'P', 'b', 'B', 'm', 'y', 'r', 'l', 'v', 'S', 'z', 's', 'h', 'L', 'kz', 'jY'],
            'symbols'     => ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'oM', "'", '.', '..'],
        ],

        /* Velthuis
         * --------
         * A case-insensitive Sanskrit encoding.
         */
        'velthuis' => [
            'vowels'      => ['a', 'aa', 'i', 'ii', 'u', 'uu', '.r', '.rr', '.li', '.ll', '', 'e', 'ai', '', 'o', 'au'],
            'other_marks' => ['.m', '.h', ''],
            'virama'      => [''],
            'consonants'  => ['k', 'kh', 'g', 'gh', '"n', 'c', 'ch', 'j', 'jh', '~n', '.t', '.th', '.d', '.d', '.n', 't', 'th', 'd', 'dh', 'n', 'p', 'ph', 'b', 'bh', 'm', 'y', 'r', 'l', 'v', '~s', '.s', 's', 'h', 'L', 'k.s', 'j~n'],
            'symbols'     => ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'o.m', "'", '|', '||'],
        ],

        /* WX
         * --
         * As terse as SLP1.
         */
        'wx' => [
            'vowels'      => ['a', 'A', 'i', 'I', 'u', 'U', 'q', 'Q', 'L', '', '', 'e', 'E', '', 'o', 'O'],
            'other_marks' => ['M', 'H', 'z'],
            'virama'      => [''],
            'consonants'  => ['k', 'K', 'g', 'G', 'f', 'c', 'C', 'j', 'J', 'F', 't', 'T', 'd', 'D', 'N', 'w', 'W', 'x', 'X', 'n', 'p', 'P', 'b', 'B', 'm', 'y', 'r', 'l', 'v', 'S', 'R', 's', 'h', '', 'kR', 'jF'],
            'symbols'     => ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'oM', "'", '|', '||'],
        ],
    ];

    // Set of names of Roman schemes.
    private $romanSchemes = [];

    // Map of alternate encodings.
    private $allAlternates = [
        'itrans' => [
            'A'    => ['aa'],
            'I'    => ['ii', 'ee'],
            'U'    => ['uu', 'oo'],
            'RRi'  => ['R^i'],
            'RRI'  => ['R^I'],
            'LLi'  => ['L^i'],
            'LLI'  => ['L^I'],
            'M'    => ['.m', '.n'],
            '~N'   => ['N^'],
            'ch'   => ['c'],
            'Ch'   => ['C', 'chh'],
            '~n'   => ['JN'],
            'v'    => ['w'],
            'Sh'   => ['S', 'shh'],
            'kSh'  => ['kS', 'x'],
            'j~n'  => ['GY', 'dny'],
            'OM'   => ['AUM'],
            '\\_'  => ['\\`'],
            '\\_H' => ['\\`H'],
            "\\'M" => ["\\'.m", "\\'.n"],
            '\\_M' => ['\\_.m', '\\_.n', '\\`M', '\\`.m', '\\`.n'],
            '.a'   => ['~'],
            '|'    => ['.'],
            '||'   => ['..'],
            'z'    => ['J'],
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

    public static function isDevanagari($string)
    {
        return preg_match(self::REGEX_DEVANAGARI, $string) > 0;
    }

    public static function isGujarati($string)
    {
        return preg_match(self::REGEX_GUJARATI, $string) > 0;
    }

    public static function isGurmukhi($string)
    {
        return preg_match(self::REGEX_GURMUKHI, $string) > 0;
    }

    public static function isKannada($string)
    {
        return preg_match(self::REGEX_KANNADA, $string) > 0;
    }

    public static function isMalayalam($string)
    {
        return preg_match(self::REGEX_MALAYALAM, $string) > 0;
    }

    public static function isOriya($string)
    {
        return preg_match(self::REGEX_ORIYA, $string) > 0;
    }

    public static function isTamil($string)
    {
        return preg_match(self::REGEX_TAMIL, $string) > 0;
    }

    public static function isTelugu($string)
    {
        return preg_match(self::REGEX_TELUGU, $string) > 0;
    }

    public static function isBengali($string)
    {
        return preg_match(self::REGEX_BENGALI, $string) > 0;
    }

    public static function isArabic($string)
    {
        return preg_match(self::REGEX_ARABIC, $string) > 0;
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
