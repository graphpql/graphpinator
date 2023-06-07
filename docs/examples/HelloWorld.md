# Hello World

This example serves as a simple tutorial on how to create a simple type and what must be done for request execution.

## Introduction

In this example, we define our simple schema and execute one request on it - all in five simple steps.

## First step - define our types

Schema is mainly defined by types. Each type is a separate class extending one of the abstract classes.

- `\Graphpinator\Typesystem\Type`
- `\Graphpinator\Typesystem\UnionType`
- `\Graphpinator\Typesystem\InterfaceType`
- `\Graphpinator\Typesystem\ScalarType`
- `\Graphpinator\Typesystem\EnumType`
- `\Graphpinator\Typesystem\InputType`

In this example, we only define one type - the `Query` type.

```php
<?php

declare(strict_types = 1);

namespace Example;

final class Query extends \Graphpinator\Typesystem\Type
{
    protected const NAME = 'Query';
    protected const DESCRIPTION = 'Graphpinator HelloWorld: Query type';

    public function validateNonNullValue($rawValue) : bool
    {
        // validation of resolved value from parent, Query is the initial type in the schema = has no parent so this function is never called
        return true;
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
    {
        // types return ResolvableFieldSet, which is a map of ResolvableField (Fields with resolve function)
        // interface only define FieldSet, which is a map of Field, which does not have resolve function but only define the signature
        return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'helloWorld',
                \Graphpinator\Typesystem\Container::String()->notNull(),
                function ($parent) : string {
                    return 'Hello world!';
                },
            ),
        ]);
    }
}
```

## Step two - create Container

All available types for our GraphQL service are stored in a `\Graphpinator\Typesystem\Container` class. 

> In this example we are going to assign the type to the `Container` manually, 
but this step can (and is recommended to) be achieved automatically when using some dependency injection.

```php
$query = new \Example\Query(); // our Query class which we defined above
$container = new \Graphpinator\SimpleContainer([$query], []);
```

## Step three - create Schema

`\Graphpinator\Typesystem\Schema` is a class that contains all information about your service.
`Schema` needs `Container` of its types, and also information which types manage different operation types (`query`, `mutation`, `subscription`).
In the following code snippet, we assign our `$container` to it and also declare that the type to manage `query` operation is our `Query` type.
In more complex services, where we would use `mutation` or `subscription` operation types, we would also pass the third and fourth parameter.

> This step is also skipped when using some dependency injection solution.

```php
$schema = new \Graphpinator\Typesystem\Schema($container, $query);
```

## Optional step - print schema definition

> This step requires `infinityloop-dev/graphpinator-printer` package.

We can use our `Schema` class to print its definition in the type language syntax, which describes the capabilities of our GraphQL service.

```php
$printer = new \Graphpinator\Printer\Printer();

echo $printer->printSchema($schema);
```

produces the following

```graphql
schema {
  query: Query
  mutation: null
  subscription: null
}

"""
Graphpinator HelloWorld: Query type
"""
type Query {
  helloWorld: String!
}
```

## Step four - create Graphpinator

`\Graphpinator\Graphpinator` is a class that brings everything together and is the main class you would be using.
It contains your `Schema`, logger, and other information needed to execute a request against your service.

```php
$graphpinator = new \Graphpinator\Graphpinator(
    $schema, 
    true,             // optional bool - whether to catch exceptions (Dont worry! Only graphpinator errors are printed in response, eg. syntax errors)
    $modules,         // optional \Graphpinator\Module\ModuleSet - extending functionality using modules
    $loggerInterface  // optional \Psr\Log\LoggerInterface - logging queries and errors
);
```

## Step five - execute Request

Final step! The last thing we need to do is to create an appropriate `\Graphpinator\Request\RequestFactory`.
Classes implementing `RequestFactory` are responsible for creation of an `Request` object, 
which is basicaly an extraction of `query`, `variables` and `operationName` from various sources.
GraPHPinator has multiple `RequestFactory` implementations ready, from which you may choose. 
All depends on how your low-level request looks like and what middleware you use.

- `\Graphpinator\Request\JsonRequestFactory` 
  - The Simplest form of request - json with keys `query`, `variables` and `operationName`.
  - Its constructor argument is `\Graphpinator\Utils\Json`.
- `\Graphpinator\Request\PsrRequestFactory` 
  - When using Psr compatible middleware.
  - The factory extracts all the information from http request itself.
  - Its constructor argument is `\Psr\Http\Message\ServerRequestInterface`.

In this simple example, we choose the `JsonRequestFactory`.

```php
$json = \Infinityloop\Utils\Json::fromString(
    '{"query":"query { helloWorld }"}'
);
$requestFactory = new \Graphpinator\Request\JsonRequestFactory($json);
$response = $graphpinator->run($requestFactory);
```

This is it, we have our response in `$response` variable. 
It is an object of class `\Graphpinator\Response` (which is `\JsonSerializable`) containing resolved data and errors.

```php
echo $response->toString();
```

produces the following

```json
{"data":{"helloWorld": "Hello world!"}}
```

### Congratulations

This is the end of the Hello world example, thank you for reading this far.
 
- For more information visit [the complete Docs](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/README.md).
- For more examples visit [the examples folder](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/examples).

## Appendix 1 - Container and Schema using Nette Dependency Injection

Example configuration for Nette framework, which automatically
- finds all types and registers them as services.
- registers `SimpleContainer` and assigns all found types
- registers `Schema`

```neon
services:
    - Graphpinator\SimpleContainer
    - Graphpinator\Typesystem\Schema(
        @Graphpinator\SimpleContainer,
        @Example\Query,
        null, # Mutation
        null # Subscription
    )
search:
    graphql:
        in: '%appDir%/GraphQL'
        extends:
            - Graphpinator\Typesystem\Contract\NamedType
```

Similar approach is surely available for other frameworks as well. 

If you are using some other framework and wish to expand this example, please send a PR.
