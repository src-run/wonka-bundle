<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Scribe\WonkaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * Class AbstractCommand.
 */
abstract class AbstractCommand extends ContainerAwareCommand
{
    /**
     * @var InputInterface
     */
    protected $input = null;

    /**
     * @var OutputInterface
     */
    protected $output = null;

    /**
     * @var array[]
     */
    protected $formatterStyles = [
        'title'     => ['white', 'black'],
        'status'    => ['black', 'blue' ],
        'status-em' => ['black', 'blue' ],
        'info-em'   => ['blue',  'white'],
        'success'   => ['green', 'white'],
        'error'     => ['red',   'white', ['bold']],
    ];

    /**
     * @return QuestionHelper
     */
    protected function getQuestionHelper()
    {
        return $this->getHelper('question');
    }

    /**
     * @param string     $question
     * @param null|mixed $default
     *
     * @return Question
     */
    protected function getQuestionString($question, $default = null)
    {
        return new Question($question, $default);
    }

    /**
     * @param string     $question
     * @param false|bool $default
     *
     * @return ConfirmationQuestion
     */
    protected function getQuestionConfirmation($question, $default = null)
    {
        return new ConfirmationQuestion($question, $default);
    }

    /**
     * @var InputInterface  $input
     * @var OutputInterface $output
     *
     * @return $this
     */
    protected function setup(InputInterface $input, OutputInterface $output)
    {
        $this->input  = $input;
        $this->output = $output;

        $this->setupFormatterStyles();

        return $this;
    }

    /**
     * @return $this
     */
    protected function setupFormatterStyles()
    {
        foreach ($this->formatterStyles as $name => $colors) {
            if (is_iterable_empty($colors) || count($colors) < 2) {
                continue;
            }

            $this->output->getFormatter()->setStyle(
                (string) $name,
                new OutputFormatterStyle($color[0], $color[1])
            );
        }

        return $this;
    }

    final protected function createTitle($title, $more = null)
    {
        if (!is_array($more)) {
            $lines = $this->breakLines($more);
        } else {
            $lines = $more;
        }

        array_unshift($lines, '');
        array_unshift($lines, $title);

        return $this->createBlock($lines);
    }

    final protected function createStatus($string, $title = null, $pad_type = STR_PAD_RIGHT)
    {
        if (!is_array($string)) {
            $lines = $this->breakLines($string);
        } else {
            $lines = $string;
        }

        if ($title !== null) {
            array_unshift($lines, '');
            array_unshift($lines, strtoupper($title));
        }

        return $this->createBlock($lines, 'cyan', 'black', [], $pad_type, false);
    }

    final protected function createConfig($string, $title = null, $pad_type = STR_PAD_RIGHT)
    {
        if (!is_array($string)) {
            $lines = $this->breakLines($string);
        } else {
            $lines = $string;
        }

        if ($title !== null) {
            array_unshift($lines, '');
            array_unshift($lines, strtoupper($title));
        }

        return $this->createBlock($lines, 'white', 'magenta', ['bold'], $pad_type);
    }

    final protected function createStatusEm($string, $title = null, $pad_type = STR_PAD_BOTH)
    {
        if (!is_array($string)) {
            $lines = $this->breakLines($string);
        } else {
            $lines = $string;
        }

        if ($title !== null) {
            array_unshift($lines, '');
            array_unshift($lines, strtoupper($title));
        }

        return $this->createBlock($lines, 'white', 'blue', [], $pad_type);
    }

    final protected function createError($string, $title = null, $pad_type = STR_PAD_BOTH)
    {
        if (!is_array($string)) {
            $lines = $this->breakLines($string);
        } else {
            $lines = $string;
        }

        if ($title !== null) {
            array_unshift($lines, strtoupper($title));
        }

        return $this->createBlock($lines, 'white', 'red', ['bold'], $pad_type);
    }

    final protected function createLine($string, $pad_type = STR_PAD_RIGHT)
    {
        if (!is_array($string)) {
            $lines = [$string];
        } else {
            $lines = $string;
        }

        return $this->createBlock($lines, 'white', 'black', [], $pad_type, false);
    }

    final protected function createLineEm($string, $pad_type = STR_PAD_RIGHT)
    {
        if (!is_array($string)) {
            $lines = [$string];
        } else {
            $lines = $string;
        }

        return $this->createBlock($lines, 'white', 'black', ['bold'], $pad_type, false);
    }

    final protected function createLineIndent($indent_i, $hasChildren = false)
    {
        if (!($indent_i > 0)) {
            return '';
        }

        $first_level_with_children = '◎───┬─';
        $first_level_no_children = '◎─────';
        $next_level_with_children = '├───┬─';
        $next_level_no_children = '├─────';

        if (count($this->writeIndentLog) > 0) {
            $this->previous_indent_i = $this->writeIndentLog[count($this->writeIndentLog) - 1];
        } else {
            $this->previous_indent_i = 0;
        }
        $this->writeIndentLog[] = $indent_i;

        if ($indent_i == 1) {
            //◎ ┤ ├ ─ ┬ └ http://www.fileformat.info/info/unicode/category/So/list.htm
            return $this->styleLineIndent(($hasChildren === true ? $first_level_with_children : $first_level_no_children).' ', 'magenta', 'black', ['bold']);
        }

        $indent_string = '    ';

        for ($i = 2; $i < $indent_i; $i++) {
            $indent_string .= '    ';
        }

        return $this->styleLineIndent($indent_string.($hasChildren === true ? $next_level_with_children : $next_level_no_children).' ', 'blue', 'black', ['bold']);
    }

    final protected function styleLineIndent($string, $fg, $bg, array $options = [])
    {
        $formattingOptions = '';
        foreach ($options as $o) {
            $formattingOptions .= "options=$o;";
        }

        $formattingTag = "fg=$fg;bg=$bg;$formattingOptions";

        return "<$formattingTag>$string</$formattingTag>";
    }

    /**
     * @param bool $human
     *
     * @return int
     */
    protected function getMemoryLimit($human = true)
    {
        $memoryLimit = ini_get('memory_limit');

        if (true === $human && false !== preg_match('/^(\d+)(.)$/', $memoryLimit, $matches)) {
            switch($matches[2]) {
                case 'G':
                    return $matches[1] * 1024 * 1024 * 1024;
                case 'M':
                    return $matches[1] * 1024 * 1024;
                case 'K':
                    return $matches[1] * 1024;
            }
        }

        return $memoryLimit;
    }

    /**
     * @return bool
     */
    protected function isMemoryOptimal()
    {
        $memoryUsage = memory_get_usage();
        $memoryLimit = $this->getMemoryLimit(false);
        $memoryMax   = $memoryLimit * .75;

        return (bool) ($memoryMax - $memoryUsage >= 0 ? true : false);
    }
}

/* EOF */
