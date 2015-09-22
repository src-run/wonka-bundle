<?php

/*
 * This file is part of the Scribe Wonka Bundle.
 *
 * (c) Scribe Inc. <oss@scr.be>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use Sami\Sami;
use Symfony\Component\Finder\Finder;
use Sami\RemoteRepository\GitHubRemoteRepository;

$projectRootPath = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('Resources')
    ->exclude('Tests')
    ->in($projectRootPath . DIRECTORY_SEPARATOR . 'src');

return new Sami($iterator, [
    'theme'                => 'default',
    'title'                => 'scribe/wonka-bundle',
    'build_dir'            => $projectRootPath . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'api',
    'cache_dir'            => $projectRootPath . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR . 'sami',
    'default_opened_level' => 3,
    'remote_repository'    => new GitHubRemoteRepository('scr-be/wonka-bundle', $projectRootPath),
]);
