php-raml-parser
===============

Coverting a [RAML definition](http://raml.org/spec.html) into accessible PHP Objects.

What it does
------------

[The RESTful API Modeling Language](http://raml.org/spec.html) is a way to describe RESTful APIs. It supports
advanced features like ``traits`` and ``resourceTypes`` to allow for easy documentation of the API.

Usage
-----

    $parser = new \Xopn\PhpRamlParser\RamlParser();
    /** @var $definition \Xopn\PhpRamlParser\Domain\Definition */
    $definition = $parser->parse('/path/to/definition.raml');
    
    // do stuff
    $definition->getAllResources();
    // etc...