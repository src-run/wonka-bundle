########
Overview
########

The *scribe/wonka-bundle* bundle is a collection of objects/interfaces to simplify bundle setup, such as advanced bundle
configuration, compiler passes, and deprecaton error handlers.

Latest Code Statistics
======================

:License:         |license|
:Build:           |travis|
:Coverage:        |coverage|
:Quality:         |scrutinizer|
:Dependencies:    |dependencies|
:Stable Release:  |packagist|
:Dev Release:     |packagistd|

Our Standards
=============

- **Continuous Integration**: Utilization of Travis CI to provide per-commit reports on the success
  or failure status of our builds.
- **Tests and Coverage**: Automated testing against our comprehensive PHPUnit  test suite, resulting
  code-coverage metrics dispatched to Coveralls .
- **Reports and Metrics**: Automated metrics pertaining to the defined code-styling guidelines, general
  code quality reports, and other statistics using Scrutinizer CI .
- **Documentation and Reference**: Comprehensive API reference generated automatically using Sami, as well
  as documentation and examples compiled using the wonderful Read the Docs  service.
- **Auto-loading**: Conformance with the PS4-4 standard, allowing for seamless inclusion in any composer
  project or any PSR-4 aware auto-loader implementation.

Documentation
=============

:General:       |docs|
:API Reference: |docsapi|

General documentation is provided via custom-written Read the Docs documentation, while automatically
generated API documentation is available for those looking to understand the code structure and possibly
implement this software within their own project.

.. |license| image:: https://img.shields.io/badge/license-MIT-008ac6.svg?style=flat-square
   :target: https://wonka-bundle.oss.scr.be/license
   :alt: The MIT License (MIT)
.. |travis| image:: https://img.shields.io/travis/scr-be/wonka-bundle/master.svg?style=flat-square
   :target: https://wonka-bundle.oss.scr.be/ci
   :alt: Travis Build Status
.. |scrutinizer| image:: https://img.shields.io/scrutinizer/g/scr-be/wonka-bundle/master.svg?style=flat-square
   :target: https://wonka-bundle.oss.scr.be/analysis
   :alt: Scrutinizer Code Quality Metrics
.. |coverage| image:: https://img.shields.io/coveralls/scr-be/wonka-bundle/master.svg?style=flat-square
   :target: https://wonka-bundle.oss.scr.be/cov
   :alt: Test Coverage Metrics
.. |dependencies| image:: https://img.shields.io/gemnasium/scr-be/wonka-bundle.svg?style=flat-square
   :target: https://wonka-bundle.oss.scr.be/dep
   :alt: Dependency Health/Status
.. |packagist| image:: https://img.shields.io/badge/packagist-no%20stable%20release-blue.svg?style=flat-square
   :target: https://wonka-bundle.oss.scr.be/pkg
   :alt: Packagist Stable Info
.. |packagistd| image:: https://img.shields.io/packagist/vpre/scr-be/wonka-bundle.svg?style=flat-square
   :target: https://wonka-bundle.oss.scr.be/pkg
   :alt: Packagist Development Info
.. |docs| image:: https://readthedocs.org/projects/scribe-wonka-bundle/badge/?version=latest&style=flat-square
   :target: https://wonka-bundle.oss.scr.be/doc
   :alt: Read the Docs Build Status
.. |docsapi| image:: https://img.shields.io/badge/docs-reference%20api-c75ec1.svg?style=flat-square
   :target: https://wonka-bundle.oss.scr.be/api
   :alt: Sami API Reference
