<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
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

    public function testGetParameter()
    {
        static::assertTrue($this->config->has('s.wonka.utility.logger_callable.class'));
        static::assertEquals(
            'Scribe\Wonka\Utility\Logger\LoggerCallable',
            $this->config->get('s.wonka.utility.logger_callable.class')
        );
    }
}

/* EOF */
