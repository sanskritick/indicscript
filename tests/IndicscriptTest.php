<?php

namespace Sanskritick\Tests;

use PHPUnit\Framework\TestCase;
use Sanskritick\Indicscript;

class IndicscriptTest extends TestCase
{
    /** @test */
    public function it_outputs_a_transliteration()
    {
        $indicscript   = new Indicscript();
        $output        = $indicscript->t('ga##Na##pa##te', 'hk', 'devanagari');

        $this->assertEquals('गNaपte', $output);
    }
}
