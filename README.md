
# [src-run] wonka-bundle

|       Travis-CI        |      Codacy Rating      |      Code Coverage      |        Style-CI         |
|:----------------------:|:-----------------------:|:-----------------------:|:-----------------------:|
| [![Travis](https://src.run/wonka-bundle/travis.svg)](https://src.run/wonka-bundle/travis) | [![Codacy](https://src.run/wonka-bundle/codacy.svg)](https://src.run/wonka-bundle/codacy) | [![Coveralls](https://src.run/wonka-bundle/coveralls.svg)](https://src.run/wonka-bundle/coveralls) | [![Coveralls](https://src.run/wonka-bundle/styleci.svg)](https://src.run/wonka-bundle/styleci) |


## Overview

[Welcome](https://src.run/go/readme_welcome)!
The `src-run/wonka-bundle` package provides the following

> a collection of classes and interfaces aimed at facilitating Symfony bundle's, including helpers for advanced bundle configuration, kernel compiler passes, and more

### Grouping

We use a Willy Wonka-inspired naming schema for our package group names. This package is part of the
[wonka group](https://src.run/wonka-bundle/group), which is a collection of packages with a focus
on "core reflection base classes" and related functionality.

You are welcome to research some [useless details](https://src.run/wonka-bundle/group_explanation)
about this specific group if you have too much time on your hands.

### JTT

This package represents a single project within a [large collection](https://src.run/go/explore) of open-source code
released under the "SR" namespace, comprised of many framework-agnostic libraries, a collection of Symfony bundles, as
well as some one-off releases. This project is authored and maintained by:

- [Rob Frawley 2nd](https://src.run/rmf)
- [Collaborators](https://src.run/wonka-bundle/github_collaborators)


## Quick Start

### Installation

Get the code by requiring it explicitly via the [Composer](https://getcomposer.com) CLI, or by editing your
*composer.json* to reflect the dependency and updating your project requirements. For example, to explicitly require
this project using the CLI, use the following command.

```bash
$ composer require src-run/wonka-bundle
```

Alternatively, to add this project to your "composer.json" file, add the following to the "require" section.

```json
require: {
	"src-run/wonka-bundle": "dev-master"
}
```

*Note: Is is not recommended to use the "dev-master" constraint. Realize that doing so could allow a release with
backwards-incompatable, breaking changes to be pulled in. Instead, it is good practive to use semantic versioning and
enter an explicit requirement. For example, to require version 1.0 you would use `^1.0`.*

To enable the bundle, register it with your Symfony application kernel by
instantiating *ScribeWonkaBundle* within the bundle array.

```php
// app/AppKernel.php
class AppKernel extends Kernel {
    public function registerBundles() {
        $bundles = [
            // ...
            new SR\WonkaBundle\WonkaBundle(),
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

This package's API reference is available on our documentation website (see the "Reference" row of the table found in
the "Additional Links" section below). All API documentation is automatically compiled against the master branch
whenever a git push event occurs.

*Static API reference for specific releases is planned and will be posted once this package has matured and reached
the version 1 milestone*.

> The API reference websites detailed above are auto-generated using a reliable and well-developed CLI tool called
> [Sami](https://src.run/go/sami). It is rigorously and regularly tested and is used for some large-scale projects, such
> as the [Symfony Full-Stack Framework](https://src.run/go/symfony) <see: https://src.run/go/symfony-api>, as well some
> smaller projects, such as [Twig](https://src.run/go/sami-twig) <see: https://src.run/go/twig-api>. Refer to Sami's
> [repository page](https://src.run/go/sami) to research usage in your own project.

### Examples/Tutorials

Currently, there is no "human-written" documentation, outside of this README (which is itself generated from a
template). Pending package stability, available man-hours, and an actual demand from external users, we may publish
a [Read the Docs](https://src.run/go/rtd) page with official documentation, tutorials, and additional resources.


## Contributing

### Discussion

For general inquiries or to discuss a broad topic or idea, find "robfrawley" on Freenode. He is always happy to 
discuss language-level ideas, possible new directions for a project, emerging technologies, as well as the weather.

### Issues

To report issues or request a new feature, use the [project issue tracker](https://src.run/wonka-bundle/github_issues).
Include as much information as possible in any bug reports. Feel free to "ping" the topic if you don't get a response
within a few days (sometimes Github notification e-mails fall through the cracks).

### Code

You created additional functionality while utilizing this package? Wonderful: send it back upstream! *Don't hesitate to
submit a pull request!* Your [imagination](https://src.run/go/readme_imagination) and the requirements outlined within
our [CONTRIBUTING.md](https://src.run/wonka-bundle/contributing) file are the only limitations.


## License

This project is licensed under the [MIT License](https://src.run/go/mit), an [FSF](https://src.run/go/fsf)- and 
[OSI](https://src.run/go/osi)-approved, [GPL](https://src.run/go/gpl)-compatible, permissive free software license.
Review the [LICENSE](https://src.run/wonka-bundle/license) file distributed with this source code for additional
information.


## Additional Links

| Item               | Result/Status                                                                                                      |
|-------------------:|:-------------------------------------------------------------------------------------------------------------------|
| __Stable Release__ | [![Packagist](https://src.run/wonka-bundle/packagist.svg)](https://src.run/wonka-bundle/packagist)     |
| __Dev Release__    | [![Packagist](https://src.run/wonka-bundle/packagist_pre.svg)](https://src.run/wonka-bundle/packagist) |
| __License__        | [![License](https://src.run/wonka-bundle/license.svg)](https://src.run/wonka-bundle/license)           |
| __Reference__      | [![License](https://src.run/wonka-bundle/api.svg)](https://src.run/wonka-bundle/api)                   |

