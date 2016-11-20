<?php

/*
 * This file is part of the `src-run/wonka-bundle` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\WonkaBundle\Tests\Twig;

use SR\Reflection\Inspect;
use SR\WonkaBundle\Test\KernelTestCase;
use SR\WonkaBundle\Twig\Definition\TwigFilterDefinition;
use SR\WonkaBundle\Twig\Definition\TwigFunctionDefinition;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;
use SR\WonkaBundle\Twig\MomentTwigExtension;
use SR\WonkaBundle\Twig\TwigExtension;

/**
 * Class TwigExtensionTest.
 */
class TwigExtensionTest extends KernelTestCase
{
    public function testSetAndGetOptions()
    {
        $extension = new TwigExtension();
        $options = new TwigOptionsDefinition();
        $options->set('is_safe', ['html']);

        $extension->setOptions($options);

        $this->assertSame(['is_safe' => ['html']], $extension->getOptions()->toArray());
    }

    public function testOptionHelperToggles()
    {
        $extension = new TwigExtension();

        $extension->addOptionNeedsEnvironment(true);
        $extension->addOptionHtmlSafe(true);

        $this->assertSame(['needs_environment' => true, 'is_safe' => ['html']], $extension->getOptions()->toArray());

        $extension->addOptionNeedsEnvironment(false);
        $extension->addOptionHtmlSafe(false);

        $this->assertSame(['needs_environment' => false, 'is_safe' => []], $extension->getOptions()->toArray());
    }

    public function testGetName()
    {
        $extension = new TwigExtension();
        $this->assertSame('twig_extension_', $extension->getName());

        $extension = new MomentTwigExtension();
        $this->assertSame('twig_extension_moment', $extension->getName());
    }

    public function testInvalidFunctionOrFilter()
    {
        $callable = function () {
            return 'test-callable';
        };

        $extension = new TwigExtension();

        $extension->addFilters([
            new TwigFilterDefinition('test', $callable),
            new \stdClass(),
        ]);
        $this->assertCount(1, $extension->getFilters());

        $extension->addFunctions([
            new TwigFunctionDefinition('test', $callable),
            new \stdClass(),
        ]);
        $this->assertCount(1, $extension->getFunctions());
    }

    public function testSetAndGetAndClearFunctions()
    {
        $callable = function () {
            return 'test-callable';
        };

        $extension = new TwigExtension();
        $extension->addFunction('test', $callable);

        if (Inspect::useClass('\Twig_Function')->isAbstract()) {
            $this->assertInstanceOf('\Twig_SimpleFunction', $extension->getFunctions()[0]);
        } else {
            $this->assertInstanceOf('\Twig_Function', $extension->getFunctions()[0]);
        }

        $extension->clearFunctions();
        $this->assertCount(0, $extension->getFunctions());
    }

    public function testSetAndGetAndClearFilters()
    {
        $callable = function () {
            return 'test-callable';
        };

        $extension = new TwigExtension();
        $extension->addFilter('test', $callable);

        if (Inspect::useClass('\Twig_Filter')->isAbstract()) {
            $this->assertInstanceOf('\Twig_SimpleFilter', $extension->getFilters()[0]);
        } else {
            $this->assertInstanceOf('\Twig_Filter', $extension->getFilters()[0]);
        }

        $extension->clearFilters();
        $this->assertCount(0, $extension->getFilters());
    }
}

/* EOF */
