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

namespace SR\WonkaBundle\Tests\Twig\Definition;

use SR\WonkaBundle\Test\KernelTestCase;
use SR\WonkaBundle\Twig\Definition\TwigOptionsDefinition;

/**
 * Class TwigOptionsDefinitionTest.
 */
class TwigOptionsDefinitionTest extends KernelTestCase
{
    public function testSetAndGetOptions()
    {
        $options = new TwigOptionsDefinition();
        $options->set('needs_environment', true);
        $options->set('is_safe', ['html']);

        $more = new TwigOptionsDefinition();
        $more->set('is_safe', []);

        $options->merge($more, false);
        $this->assertSame(['needs_environment' => true, 'is_safe' => ['html']], $options->toArray());

        $options->merge($more);
        $this->assertSame(['needs_environment' => true, 'is_safe' => []], $options->toArray());
    }
}

/* EOF */
