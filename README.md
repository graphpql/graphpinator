# GraPHPinator 

:zap::globe_with_meridians::zap: Easy-to-use & Fast GraphQL server implementation for PHP.

## Introduction

Feature complete PHP implementation of GraphQL server. Its job is transformation of query string into resolved Json result for a given Schema. 

- Aims to be compliant with the latest draft of GraphlQL specification.
- Fully typesafe, and therefore minimum required PHP version is 8.0.
- Sacrafices a tiny bit of convenience for huge amount of clarity and safety - no random configuration `array`s, no mixed types, no variable function arguments ... - this library doesnt try to save you from verbosity, but makes sure you always know what you've got.
- Includes some opt-in extensions which are out of scope of official specs:
    - [Extra types](https://github.com/infinityloop-dev/graphpinator-extra-types) - some useful and commonly used types, both scalar or composite
    - [Constraint directives](https://github.com/infinityloop-dev/graphpinator-constraint-directives) - typesystem directives to declare additional validation on top of GraphQL type system
    - [Where directives](https://github.com/infinityloop-dev/graphpinator-where-directives) - executable directive to filter values in lists
    - File upload using [multipart-formdata](https://github.com/jaydenseric/graphql-multipart-request-spec) specs (currently bundled)

## Installation

Install package using composer

```composer require infinityloop-dev/graphpinator```

## Dependencies

|PHP version|Package version|Build status|
|-----------|---------------|------------|
|7.4+|0.25.x|[![stable 7.4](https://github.com/infinityloop-dev/graphpinator/workflows/PHP/badge.svg?branch=php74_bugfixes)](https://github.com/infinityloop-dev/graphpinator/actions?query=branch%3Aphp74_bugfixes) [![codecov](https://codecov.io/gh/infinityloop-dev/graphpinator/branch/php74_bugfixes/graph/badge.svg)](https://codecov.io/gh/infinityloop-dev/graphpinator)|
|8.0.1+|1.x|[![master 8.0](https://github.com/infinityloop-dev/graphpinator/workflows/PHP/badge.svg?branch=master)](https://github.com/infinityloop-dev/graphpinator/actions?query=branch%3Amaster) [![codecov](https://codecov.io/gh/infinityloop-dev/graphpinator/branch/master/graph/badge.svg)](https://codecov.io/gh/infinityloop-dev/graphpinator)|

- [infinityloop-dev/utils](https://github.com/infinityloop-dev/utils)

## How to use

- Visit our simple [Hello world example](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/examples/HelloWorld.md).
- Or visit [the complete Docs](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/README.md).

## Contributing

This package is relatively new so some features might be missing. If you stumble upon something that is not included or is not compliant with the specs, please inform us by creating an issue or discussion. This is not yet another package, where issues and pull-requests lie around for months, so dont hesitate and help us improve the library.
