# GraPHPinator [![PHP](https://github.com/infinityloop-dev/graphpinator/workflows/PHP/badge.svg?branch=master)](https://github.com/infinityloop-dev/graphpinator/actions?query=workflow%3APHP) [![codecov](https://codecov.io/gh/infinityloop-dev/graphpinator/branch/master/graph/badge.svg)](https://codecov.io/gh/infinityloop-dev/graphpinator)

:zap::globe_with_meridians::zap: Easy-to-use & Fast GraphQL server implementation for PHP.

## Introduction

Feature complete PHP implementation of GraphQL server. Its job is transformation of query string into resolved Json result for a given Schema. 

- Aims to be compliant with the latest draft of GraphlQL specification.
- Includes some opt-in extensions which are out of scope of official specs, such as custom scalars, constraint directives and file upload using [multipart-formdata](https://github.com/jaydenseric/graphql-multipart-request-spec) specs.
- Fully typesafe, and therefore minimum required PHP version is 7.4+.
- Sacrafices a tiny bit of convenience for huge amount of clarity and safety - no random configuration `array`s, no variable function arguments, ... - this library doesnt try to save you from verbosity, but makes sure you always know what you've got.

## Installation

Install package using composer

```composer require infinityloop-dev/graphpinator```

## Dependencies

- PHP >= 8.0 (or >= 7.4 for supported 0.25.x release)
- [infinityloop-dev/utils](https://github.com/infinityloop-dev/utils)

## How to use

- Visit our simple [Hello world example](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/examples/HelloWorld.md).
- Or visit [the complete Docs](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/README.md).

## Contributing

This package is relatively new so some features might be missing. If you stumble upon something that is not included or is not compliant with the specs, please inform us by creating an issue. This is not yet another package, where issues and pull-requests lie around for months, so dont hesitate and help us improve the library.
