<?php

/*
 * This file is part of the `src-run/wonka-bundle` project.
 *
 * (c) Rob Frawley 2nd <rmf@src.run>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace SR\WonkaBundle\Twig;

/**
 * Class MomentTwigExtension.
 */
class MomentTwigExtension extends TwigExtension
{
    public function __construct()
    {
        parent::__construct();

        $this->addOptionHtmlSafe();
        $this->addFilter('moment', [$this, 'moment']);
    }

    /**
     * @param \DateTime $ago
     *
     * @return string
     */
    public function moment(\DateTime $ago)
    {
        $now = new \DateTime();
        $delta = $now->format('U') - $ago->format('U');
        $deltaAbs = abs($delta);

        $momentString = [
            'just now',
            'moments ago',
            'in past hour',
            'a few hours ago',
            'in past day',
            'in past two days',
            'in past week',
            'in past two weeks',
            'in past month',
            'over a month ago',
            'ages ago',
        ];

        $momentTimes = [
            1 * 60,
            10 * 60,
            60 * 60,
            4 * 60 * 60,
            24 * 60 * 60,
            2 * 24 * 60 * 60,
            7 * 24 * 60 * 60,
            2 * 7 * 24 * 60 * 60,
            30 * 24 * 60 * 60,
            3 * 30 * 24 * 60 * 60,
        ];

        $i = 0;

        for ($j = 0; $j < count($momentTimes); ++$j) {
            if ($deltaAbs > $momentTimes[$j]) {
                ++$i;
            }
        }

        return $momentString[$i];
    }
}

/* EOF */
