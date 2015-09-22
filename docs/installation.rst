############
Installation
############

Using Composer
==============

Add Package Dependency
----------------------

To include this bundle in your project, simply add it as a dependency within the ``require`` block of your project's ``composer.json`` file. You must
add something like the following:

.. code-block:: json

    "require" : {
      "scribe/wonka-bundle" : "dev-master"
    }

.. topic:: Note

    The above composer requirement shows including version ``dev-master``, though you may want to use an official release. If this is the case, visit
    out `Packagist Page <https://packagist.org/packages/scribe/wonka-bundle>`_ and find the version you'd like. For information on composer's version
    syntax, reference `their official documentation <https://getcomposer.org/doc/articles/versions.md>`_. For example, if you wanted to include
    version ``0.1`` and allow it to upgrade to new stable versions when the ``minor`` version number increases, you would use the version string ``~0.1``
    in your composer requirements array.

Update Installed Dependencies
-----------------------------

Next, run ``composer`` to update your vendor dependencies and generate new autoloader files for your project.

.. code-block:: bash

    composer.phar update

Register Bundle
---------------

Lastly, the bundle must be enabled within Symfony's ``AppKernel.php`` before it is registered with your application for use. Generally, you can find
the required file at ``app/AppKernel.php`` within your project root. You will see a file similar to the example provided, where you need to locate
the ``$bundles`` array:

.. code-block:: php

    <?php

    use Symfony\Component\HttpKernel\Kernel;
    use Symfony\Component\Config\Loader\LoaderInterface;

    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                // OPTION 1 (Common)
                // Use this location to load the bundle in all enviornments (production, dev, test, or any others you may have setup)
            );

            if (in_array($this->getEnvironment(), array('dev', 'test'))) {
                // OPTION 2 (Uncommon)
                // Use this location to load the bundle in only the dev/test enviornments.
            }

            return $bundles;
        }
    }

Within the array marked as ``OPTION 1`` enter ``ScribeWonkaBundle(),`` to register the bundle with your application. If you believe you need
``OPTION 2`` it is advisable that you read the Symfony documentation. Here is what the ``$bundle`` array should look like:

.. code-block:: php

    $bundles = array(
        // 
        // a bunch of symfony full-stack bundles
        //
        // this bundle:
        new ScribeWonkaBundle(),
        //
        // other custom bundles you have enabled
        // 
        // your application bundle
        //
    );

After completing the above, you are ready to go!
