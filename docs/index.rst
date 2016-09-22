Description Enhancer for Doctrine PHPCR-ODM 
===========================================

Description Enhancer for the PSI Description component.

Adds standard descriptors from Doctrine PHPCR-ODM metadata.

- Adds hierarchy descriptors.
- Sets the ``std.class`` descriptor to the **real** class when a proxy is given.

Usage:

.. code-block:: php

    <?php

    use Psi\Component\Description\DescriptionFactory;
    use Psi\Component\Description\Enhancer\Doctrine\PhpcrOdmEnhancer;

    $factory = new DescriptionFactory([
        new PhpcrOdmEnhancer()
    ]);
