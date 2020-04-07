# GraPHPinator ![PHP](https://github.com/infinityloop-dev/graphpinator/workflows/PHP/badge.svg?branch=master)

:zap: :globe_with_meridians: :zap: Easy-to-use & Fast GraphQL server implementation for PHP.

## Introduction

PHP implementation of GraphQL server. Its job is transformation of query string into resolved Json result for a given Schema.

## Installation

Install package using composer

```composer require infinityloop-dev/graphpinator```

## Dependencies

- PHP >= 7.4
- [infinityloop-dev/utils](https://github.com/infinityloop-dev/utils)

## How to use

- Define Schema for your GraphQL service. [Detailed description of this step](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/DefiningSchema.md)
- Execute request.
```
$request = 'query queryName ($var1: String!) { field1 { innerField1 innerField2(arg1: $var1) } }';
$variables = \Infinityloop\Utils\Json::fromArray(['var1' => 'value']);

$graphpinator = new \Graphpinator\Graphpinator($schema);
$result = $graphpinator->runQuery($request, $variables);
```
- For more information [visit the Docs](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/README.md)

## Contributing

This package is relatively new so some features might be missing. If you stumble upon something that is not included or is not compliant with the specs, please inform us by creating an issue. This is not yet another package, where issues and pull-requests lie around for months, so dont hesitate and help us improve the library.
