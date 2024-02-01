# GraPHPinator [![PHP](https://github.com/graphpql/graphpinator/actions/workflows/php.yml/badge.svg)](https://github.com/graphpql/graphpinator/actions/workflows/php.yml) [![codecov](https://codecov.io/gh/infinityloop-dev/graphpinator/branch/master/graph/badge.svg)](https://codecov.io/gh/infinityloop-dev/graphpinator)

:zap::globe_with_meridians::zap: Easy-to-use & Fast GraphQL server implementation for PHP.

## Introduction

Feature complete PHP implementation of GraphQL server. Its job is transformation of query string into resolved Json result for a given Schema. 

- Aims to be compliant with the **latest draft of GraphQL specification** and its RFCs.
- Fully typesafe, and therefore **minimum required PHP version is 8.1**. Sacrifices a tiny bit of convenience for huge amount of clarity and safety - no random configuration `array`s, no mixed types, no variable function arguments - this library doesnt try to save you from verbosity, but makes sure you always know what you've got.
- Code first.
- Flexible. Easy to extend with extra functionality using Modules or middleware Directives.
- Includes some opt-in extensions which are out of scope of official specs:

|Name|Version|Description|
|---|---|---|
|[Printer](https://github.com/graphpql/graphpinator-printer)|![GitHub release](https://img.shields.io/github/v/release/graphpql/graphpinator-printer?label=version)|Schema printing for GraPHPinator typesystem.|
|[Extra types](https://github.com/graphpql/graphpinator-extra-types)|![GitHub release](https://img.shields.io/github/v/release/graphpql/graphpinator-extra-types?label=version)|Some useful directives and commonly used types, both scalar or composite.|
|[Constraint directives](https://github.com/graphpql/graphpinator-constraint-directives)|![GitHub release](https://img.shields.io/github/v/release/graphpql/graphpinator-constraint-directives?label=version)|Typesystem directives to declare additional validation on top of GraphQL typesystem.|
|[Where directives](https://github.com/graphpql/graphpinator-where-directives)|![GitHub release](https://img.shields.io/github/v/release/graphpql/graphpinator-where-directives?label=version)|Executable directives to filter values in lists.|
|[Upload](https://github.com/graphpql/graphpinator-upload)|![GitHub release](https://img.shields.io/github/v/release/graphpql/graphpinator-upload?label=version)|Module to handle [multipart-formdata](https://github.com/jaydenseric/graphql-multipart-request-spec) requests.|
|[Query cost](https://github.com/graphpql/graphpinator-query-cost)|![GitHub release](https://img.shields.io/github/v/release/graphpql/graphpinator-query-cost?label=version)|Modules to limit query cost by restricting maximum depth or number of nodes.|
|[Persisted queries](https://github.com/graphpql/graphpinator-persisted-queries)|![GitHub release](https://img.shields.io/github/v/release/graphpql/graphpinator-persisted-queries?label=version)|Module to persist validated query in cache and improve performace of repeating queries.|

- Includes adapters for easy integration into other PHP frameworks:

|Name|Version|Description|
|---|---|---|
|PSR|Bundled||
|[Nette](https://github.com/graphpql/graphpinator-nette)|![GitHub release](https://img.shields.io/github/v/release/graphpql/graphpinator-nette?label=version)|Adapters for [Nette framework](https://nette.org/).|

- Project is composed of multiple smaller packages, which may be used standalone:

|Name|Version|Description|
|---|---|---|
|[Source](https://github.com/graphpql/graphpinator-source)|![GitHub release](https://img.shields.io/github/v/release/graphpql/graphpinator-source?label=version)|Wrapper around source document.|
|[Tokenizer](https://github.com/graphpql/graphpinator-tokenizer)|![GitHub release](https://img.shields.io/github/v/release/graphpql/graphpinator-tokenizer?label=version)|Lexical analyzer of GraphQL document.|
|[Parser](https://github.com/graphpql/graphpinator-parser)|![GitHub release](https://img.shields.io/github/v/release/graphpql/graphpinator-parser?label=version)|Syntactic analyzer of GraphQL document.|

## Installation

Install package using composer

```composer require infinityloop-dev/graphpinator```

## How to use

- Visit our simple [Hello world example](https://github.com/graphpql/graphpinator/blob/master/docs/examples/HelloWorld.md).
- Or visit [the complete Docs](https://github.com/graphpql/graphpinator/blob/master/docs/README.md).

## Dependencies

- PHP 8.1+ 
  - Latest PHP 8.0+ version is 1.3.x
  - Latest PHP 7.4+ version is 0.25.x
- [infinityloop-dev/utils](https://github.com/infinityloop-dev/utils)
- [psr/http-message](https://github.com/php-fig/http-message)
- [psr/log](https://github.com/php-fig/log)

This list excludes graphpinator sub-packages such as graphpinator-common, graphpinator-tokenizer and others.

## Supporters

This project is being made with help by following companies and individuals. Thank you!

[![Webthinx](docs/supporters/webthinx.png "Webthinx")](https://www.webthinx.com/)

## Contributing

This package is relatively new, so some features might be missing. If you stumble upon something that is not included or is not compliant with the specs, please inform us by creating an issue or discussion. This is not yet another package, where issues and pull-requests lie around for months, so dont hesitate and help us improve the library.
