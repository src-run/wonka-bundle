<?php

/*
 * This file is part of the `src-run/wonka-bundle` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\WonkaBundle\Tests\Test;

use SR\WonkaBundle\Test\KernelTestCase;

class KernelTestCaseTest extends KernelTestCase
{
    public function testContainer()
    {
        $this->assertTrue($this->hasContainer());
        $this->assertInstanceOf('Symfony\Component\DependencyInjection\ContainerInterface', $this->getContainer());
        $this->assertNotNull($this->getService('s.wonka.reflection_analyser'));
        $this->assertNotNull($this->getParameter('s.wonka.reflection_analyser.class'));
    }

    public function testGetInvalidService()
    {
        $this->setExpectedException('\RuntimeException');

        $this->getService('does-not-exist');
    }

    public function testGetInvalidParameter()
    {
        $this->setExpectedException('\RuntimeException');

        $this->getParameter('does-not-exist');
    }

    public function testSetUpBeforeClass()
    {
        $test = new KernelTestCase();
        $reflection = new \ReflectionObject($test);

        $property = $reflection->getProperty('container');
        $property->setAccessible(true);

        $method = $reflection->getMethod('getContainer');
        $method->setAccessible(true);

        $property->setValue($test, null);
        $this->assertNotInstanceOf('Symfony\Component\DependencyInjection\ContainerInterface', $method->invoke($test));

        KernelTestCase::setUpBeforeClass();
        $this->assertInstanceOf('Symfony\Component\DependencyInjection\ContainerInterface', $method->invoke($test));
    }

    public function testTearDownAfterClass()
    {
        $test = new KernelTestCase();
        $reflection = new \ReflectionObject($test);

        $property = $reflection->getProperty('container');
        $property->setAccessible(true);

        $method = $reflection->getMethod('getContainer');
        $method->setAccessible(true);

        $dir = static::$container->getParameter('kernel.cache_dir');

        $this->assertTrue(scandir($dir) > 5);
        KernelTestCase::tearDownAfterClass();
        $this->assertCount(2, scandir($dir));
    }
}

/* EOF */
