# [src-run] wonka-bundle

| Continuous Integration |   Code Quality Review   |    UnitTest Coverage    |
|:----------------------:|:-----------------------:|:-----------------------:|
| [![Travis](https://src.run/wonka-bundle/travis_shield)](https://src.run/wonka-bundle/travis) | [![Codacy](https://src.run/wonka-bundle/codacy_shield)](https://src.run/wonka-bundle/codacy) | [![Coveralls](https://src.run/wonka-bundle/coveralls_shield)](https://src.run/wonka-bundle/coveralls) |

## Overview

[Welcome](https://src.run/go/readme_welcome)!
The `src-run/wonka-bundle` package provides
a collection of classes and interfaces aimed at facilitating Symfony
bundle's, including helpers for advanced bundle configuration, kernel
compiler passes, and more.

### Grouping

This package is part of the [wonka](https://src.run/wonka-bundle/group)
group ([explanation](https://src.run/wonka-bundle/group_explanation)),
comprised of other releases with a concentration in
*core, rudimentary routines*,
and related functionality.

### JTT

This package represents a single project within a
[large collection](https://src.run/go/explore) of open-source code released
under the *SR* namespace, comprised of framework-agnostic libraries,
and a number of Symfony bundles. These projects are authored and maintained
by [Rob Frawley 2nd](https://src.run/rmf) and
[collaborators](https://src.run/wonka-bundle/github_collaborators).


## Quick Start

### Installation

Get the code by requiring it explicitly via the [Composer](https://getcomposer.com)
CLI, or by editing your *composer.json* to reflect the dependency and updating
your project requirements. For example, to explicitly require this project using
the CLI, use the following command.

```bash
$ composer require src-run/wonka-bundle
```

To enable the bundle, register it with your Symfony application kernel by
instantiating *ScribeWonkaBundle* within the bundle array.

```php
// app/AppKernel.php
class AppKernel extends Kernel {
    public function registerBundles() {
        $bundles = [
            // ...
            new SR\WonkaBundle\ScribeWonkaBundle(),
        ];
        // ...
    }
}
```

### Configuration

Available configuration values can be referenced by using the Symfony console command
`app/console` (assuming the Symfony full-stack framework and bundle registration).
Additionally, this bundle provides a bare console executable that can be invoked by calling
the following.

```bash
bin/wonka config:dump-reference scribe_wonka
```

## Reference

### API Docs

This package's API-documentation is available at [src.run/wonka-bundle/api](https://src.run/wonka-bundle/api),
(as well as linked below via the *Reference* badge found under the *Additional Links*
header). All API-reference is build against the *master* Git branch and updated
automatically on each Git push---api-reference for *specific releases* will
be provided once this package has matured.

> The entire API-reference website is auto-generated using a quick,
> reliable, and well-developed CLI tool called [Sami](https://src.run/go/sami).
> It is rigerously and regularly tested through its use in large, complex projects,
> such as the [Symfony Full-Stack Framework](https://src.run/go/symfony)
> <see: [src.run/go/api-ref-symfony](https://src.run/go/symfony-api)>, as well
> as its use in smaller projects such
> [Twig](https://src.run/go/sami-twig)
> <see: [src.run/go/api-ref-twig](https://src.run/go/twig-api)>.
> Reference Sami's [GitHub page](https://src.run/go/sami) to learn how to use
> it with your own projects!

### Examples/Tutorials

Currently, there is no *"human-written"* documentation---outside of this README.
Pending package stability and available resources, a
[RTD (Read the Docs)](https://src.run/go/rtd) page will be published with
additional information and tutorials, including real use-cases within the Symfony
Framework.

## Contributing

### Discussion

For general inquiries or to discuss a broad topic or idea, you can find
*robfrawley* on Freenode. There is also a *#scribe* channel, which can
be joined using the following link
[irc.choopa.net:6669/scribe](irc://irc.choopa.net:6669/scribe).

### Issues

To report issues or request a new feature use
[GitHub](https://src.run/wonka-bundle/github_issues)
or [GitLab](https://src.run/wonka-bundle/gitlab_issues)
to start a discussion. Include as much information as possible to aid in
a quick resolution. Feel free to "ping" the topic if you don't get a
response within a few days.

### Code

You created additional functionality during the use of this package? Send
it back upstream! *Don't hesitate to submit a pull request!* Beyond the
brief requirements outlined in the
[contibuting guide](https://src.run/wonka-bundle/contributing),
your [imagination](https://src.run/go/readme_imagination)
represents the only limitation.

## License

This project is licensed under the
[MIT License](https://src.run/go/mit), an
[FSF](https://src.run/go/fsf)-/[OSI](https://src.run/go/osi)-approved
and [GPL](https://src.run/go/gpl)-compatible, permissive free software
license. Review the
[LICENSE](https://src.run/wonka-bundle/license)
file distributed with this source code for additional information.

## Additional Links

|       Purpose | Status        |
|--------------:|:--------------|
| *Stable Release*    | [![Packagist](https://src.run/wonka-bundle/packagist_shield)](https://src.run/wonka-bundle/packagist) |
| *Dev Release*    | [![Packagist](https://src.run/wonka-bundle/packagist_pre_shield)](https://src.run/wonka-bundle/packagist) |
| *License*    | [![License](https://src.run/wonka-bundle/license_shield)](https://src.run/wonka-bundle/license) |
| *Reference*  | [![License](https://src.run/wonka-bundle/api_shield)](https://src.run/wonka-bundle/api) |
