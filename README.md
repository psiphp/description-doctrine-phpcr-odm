# Symfony CMF Description Component

[![Build Status](https://travis-ci.org/symfony-cmf/description.svg?branch=master)](https://travis-ci.org/symfony-cmf/description)
[![StyleCI](https://styleci.io/repos/26994566/shield)](https://styleci.io/repos/26994566)
[![Latest Stable Version](https://poser.pugx.org/symfony-cmf/description/version.png)](https://packagist.org/packages/symfony-cmf/description)
[![Total Downloads](https://poser.pugx.org/symfony-cmf/description/d/total.png)](https://packagist.org/packages/symfony-cmf/description)

This component is part of the [Symfony Content Management Framework (CMF)](http://cmf.symfony.com/)
and licensed under the [MIT License](LICENSE).

The Description component provides a way for descriptions to be provided for
your objects.

A description is made up of *descriptors*, examples of descriptors are:

- A title to use for your object.
- Thumbnail image URL for your object.
- Edit URL for your object.
- URLs for creating children of your object.
- etc.

Descriptors are provided by *enhancers*. Enhancers enhance the description of
your object. Some existing enhancers:

- **SonataAdminEnhancer**: Provide CRUD links and titles for objects based on
  Sonata metadata.
- **SyliusAdminEnhancer**: Same as above but for Sylius.
- **PhpcrOdmEnhancer**: Provide information about valid children types.
- **SycmsContentTypeEnhancer**: Provide image and CRUD URLs, titles and more.

## Requirements 

* See also the `require` section of [composer.json](composer.json)

## Documentation

This library is under development and as yet there is no documentation.

See also:

* [All Symfony CMF documentation](http://symfony.com/doc/master/cmf/index.html) - complete Symfony CMF reference
* [Symfony CMF Website](http://cmf.symfony.com/) - introduction, live demo, support and community links

## Contributing

Pull requests are welcome. Please see our
[CONTRIBUTING](https://github.com/symfony-cmf/symfony-cmf/blob/master/CONTRIBUTING.md)
guide.

Unit and/or functional tests exist for this component. See the
[Testing documentation](http://symfony.com/doc/master/cmf/components/testing.html)
for a guide to running the tests.

Thanks to
[everyone who has contributed](https://github.com/symfony-cmf/Description/contributors) already.

