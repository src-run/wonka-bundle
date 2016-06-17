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

namespace SR\WonkaBundle\Tests\Utility\Config;

use SR\WonkaBundle\Utility\TestCase\KernelTestCase;
use SR\WonkaBundle\Utility\Config\ConfigContainer;

class ConfigContainerTest extends KernelTestCase
{
    /**
     * @var ConfigContainer
     */
    protected $config;

    public function setUp()
    {
        parent::setUp();

        $this->config = new ConfigContainer($this->container);
    }

    public function testHasParameter()
    {
        static::assertTrue($this->config->has('s.wonka.invokable_logger.class'));
    }

    public function testGetParameter()
    {
        static::assertSame(
            'SR\Wonka\Utility\Logger\InvokableLogger',
            $this->config->get('s.wonka.invokable_logger.class')
        );
    }

    public function testGetInvalidParameter()
    {
        static::assertFalse($this->config->has('s.avcdefghijklmnopqrstuvwxyz0123456789'));
        static::assertNotEquals(
            'SR\Wonka\Utility\Logger\InvokableLogger',
            $this->config->get('s.avcdefghijklmnopqrstuvwxyz0123456789')
        );
        static::assertNull($this->config->get('s.avcdefghijklmnopqrstuvwxyz0123456789'));
    }
}

/* EOF */
