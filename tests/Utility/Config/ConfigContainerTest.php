<?php

/*
 * This file is part of the Wonka Bundle.
 *
 * (c) Scribe Inc.     <scr@src.run>
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\Tests\Utility\Config;

use Scribe\WonkaBundle\Utility\TestCase\KernelTestCase;
use Scribe\WonkaBundle\Utility\Config\ConfigContainer;

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
            'Scribe\Wonka\Utility\Logger\InvokableLogger',
            $this->config->get('s.wonka.invokable_logger.class')
        );
    }

    public function testGetInvalidParameter()
    {
        static::assertFalse($this->config->has('s.avcdefghijklmnopqrstuvwxyz0123456789'));
        static::assertNotEquals(
            'Scribe\Wonka\Utility\Logger\InvokableLogger',
            $this->config->get('s.avcdefghijklmnopqrstuvwxyz0123456789')
        );
        static::assertNull($this->config->get('s.avcdefghijklmnopqrstuvwxyz0123456789'));
    }
}

/* EOF */
