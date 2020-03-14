<?php

namespace Sanskritick\Script\Tests;

use PHPUnit\Framework\TestCase;
use Sanskritick\Script\Indicscript;

class IndicscriptTest extends TestCase
{
    /** @test */
    public function it_outputs_a_transliteration()
    {
        $indicscript   = new Indicscript();
        $output        = $indicscript->t('ga##Na##pa##te', 'hk', 'devanagari');

        $this->assertEquals($output, 'गNaपte');
    }
}
