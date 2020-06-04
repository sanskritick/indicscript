<?php

namespace Sanskritick\Tests;

use Sanskritick\IndicScript;
use PHPUnit\Framework\TestCase;

class IndicScriptTest extends TestCase
{
    /** @test */
    public function it_outputs_a_transliteration()
    {
        $indicscript   = new IndicScript();
        $output        = $indicscript->transliterate('ga##Na##pa##te', 'hk', 'devanagari');

        $this->assertEquals('गNaपte', $output);
    }

    /** @test */
    public function it_detects_devanagari()
    {
        $this->assertTrue(IndicScript::isDevanagari('सदाऽऽत्मा'));
        $this->assertFalse(IndicScript::isDevanagari('This is English text'));
    }
}
