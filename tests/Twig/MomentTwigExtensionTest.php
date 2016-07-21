<?php

/*
 * This file is part of the `src-run/wonka-bundle` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 * (c) Scribe Inc      <scr@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\WonkaBundle\Tests\Twig;

use SR\WonkaBundle\Test\KernelTestCase;
use SR\WonkaBundle\Twig\MomentTwigExtension;

/**
 * Class MomentTwigExtensionTest.
 */
class MomentTwigExtensionTest extends KernelTestCase
{
    /**
     * @dataProvider momentProvider
     *
     * @param \DateTime $provided
     * @param string    $expected
     */
    public function testMomentFilter($expected, $provided)
    {
        $moment = new MomentTwigExtension();
        $output = $moment->moment($provided);

        $this->assertEquals($output, $expected);
    }

    /**
     * @return array
     */
    public static function momentProvider()
    {
        return [
            ['just now', \DateTime::createFromFormat('U', strtotime('-50 seconds'))],
            ['moments ago', \DateTime::createFromFormat('U', strtotime('-2 minutes'))],
            ['moments ago', \DateTime::createFromFormat('U', strtotime('-9 minutes'))],
            ['in past hour', \DateTime::createFromFormat('U', strtotime('-11 minutes'))],
            ['in past hour', \DateTime::createFromFormat('U', strtotime('-55 minutes'))],
            ['a few hours ago', \DateTime::createFromFormat('U', strtotime('-2 hours'))],
            ['a few hours ago', \DateTime::createFromFormat('U', strtotime('-3 hours'))],
            ['in past day', \DateTime::createFromFormat('U', strtotime('-5 hours'))],
            ['in past day', \DateTime::createFromFormat('U', strtotime('-23 hours'))],
            ['in past two days', \DateTime::createFromFormat('U', strtotime('-25 hours'))],
            ['in past two days', \DateTime::createFromFormat('U', strtotime('-47 hours'))],
            ['in past week', \DateTime::createFromFormat('U', strtotime('-3 days'))],
            ['in past week', \DateTime::createFromFormat('U', strtotime('-6 days'))],
            ['in past two weeks', \DateTime::createFromFormat('U', strtotime('-8 days'))],
            ['in past two weeks', \DateTime::createFromFormat('U', strtotime('-13 days'))],
            ['in past month', \DateTime::createFromFormat('U', strtotime('-15 days'))],
            ['in past month', \DateTime::createFromFormat('U', strtotime('-29 days'))],
            ['over a month ago', \DateTime::createFromFormat('U', strtotime('-31 days'))],
            ['over a month ago', \DateTime::createFromFormat('U', strtotime('-58 days'))],
            ['ages ago', \DateTime::createFromFormat('U', strtotime('-3 months'))],
            ['ages ago', \DateTime::createFromFormat('U', strtotime('-12 months'))],
            ['ages ago', \DateTime::createFromFormat('U', strtotime('-10 years'))],
      ];
    }
}

/* EOF */
