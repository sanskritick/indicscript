<?php

namespace Sanskritick\Tests;

class IndicScriptOptions extends IndicscriptBase
{
    public function testHindiStyleTransliteration()
    {
        $f = $this->transHelper('itrans', 'devanagari', ['syncope' => true]);
        $f('karaN', 'करण');
        $f('rAj ke lie', 'राज के लिए');
    }

    public function testSkippingSGML()
    {
        $f1 = $this->transHelper('hk', 'devanagari');
        $f2 = $this->transHelper('hk', 'devanagari', ['skip_sgml' => false]);
        $f3 = $this->transHelper('hk', 'devanagari', ['skip_sgml' => true]);
        $f1('<p>nara iti</p>', '<प्>नर इति</प्>');
        $f2('<p>nara iti</p>', '<प्>नर इति</प्>');
        $f3('<p>nara iti</p>', '<p>नर इति</p>');
        $f3('##<p>nara iti</p>', '<p>nara iti</p>');
    }
}
