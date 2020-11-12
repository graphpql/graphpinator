# Hello World

In this section, we define our simple schema and execute the simple request on it - all in five simple steps.

## First step - define our types

Schema is mainly defined by types. Each type is a separate class extending one of the abstract classes.

- `\Graphpinator\Type\Type`
- `\Graphpinator\Type\UnionType`
- `\Graphpinator\Type\InterfaceType`
- `\Graphpinator\Type\ScalarType`
- `\Graphpinator\Type\EnumType`
- `\Graphpinator\Type\InputType`

In this example, we only define one type - the Query type.

```
<?php

declare(strict_types = 1);

namespace Example;

final class Query extends \Graphpinator\Type\Type
{
    protected const NAME = 'Query';
    protected const DESCRIPTION = 'Graphpinator Hello world example type'.

    protected function validateNonNullValue($rawValue) : bool
    {
        // validation of resolved value from parent, Query is the initial type in the schema = has no parent ($rawValue is null)
        return true;
    }

    protected function getFieldDefinition(): \Graphpinator\Field\ResolvableFieldSet
    {
        // types return ResolvableFieldSet, which is a map of ResolvableField (Fields with resolve function)
        // interface only define FieldSet, which is a map of Field, which does not have resolve function but only define the signature
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'helloWorld',
                \Graphpinator\Container\Container::String()->notNull,
                function ($parent) : string {
                  return 'Hello world!';
                },
            ),
        ]);
    }
}
```

## Step two - create Container

All available types for our GraphQL service are stored in a `\Graphpinator\Container\Container` class. 

> In this example we are going to assign the type to the `Container` manually, 
but this step can (and is recommended to) be achieved automatically when using some dependency injection.

```
$query = new \Example\Query(); // our Query class which we defined above
$container = new \Graphpinator\Container\SimpleContainer([$query]);
```

## Step three - create Schema

`\Graphpinator\Type\Schema` is a class that contains all information about your service.
`Schema` needs `Container` of its types, and also information which types manage different operation types (`query`, `mutation`, `subscription`).
In the following code snippet, we assign our `$container` to it and also declare that the type to manage `query` operation is our `Query` type.
In more complex services, where we would use `mutation` or `subscription` operation types, we would also pass the third and fourth parameter.

> This step is also skipped when using some dependency injection solution.

```
$schema = new \Graphpinator\Type\Schema($container, $query);
```

## Optional step - print schema definition

We can use our `Schema` class to print its definition in the type language syntax, which describes the capabilities of our GraphQL service.

```
echo $schema->printSchema();
```

produces the following

```
schema {
  query: Query
  mutation: null
  subscription: null
}

"""
Graphpinator Hello world example type
"""
type Query {
  helloWorld: String!
}
```

## Step four - create Graphpinator

`\Graphpinator\Graphpinator` is a class that brings everything together and is the main class you would be using.
It contains your `Schema`, logger, and other information needed to execute a request against your service.

```
$graphpinator = new \Graphpinator\Graphpinator(
    $schema, 
    true,            // optional bool - whether to catch exceptions (Dont worry! Only graphpinator errors are printed in response, eg. syntax errors)
    null,            // optional \Graphpinator\Module\ModuleSet - extending functionality using modules
    $loggerInterface // optional \Psr\Log\LoggerInterface - logging queries and errors
);
```

## Step five - execute Request

Final step! The last thing we need to do is to create an appropriate `\Graphpinator\Request\RequestFactory`.
GraPHPinator may have multiple RequestFactory implementations that depend on how your low-level request looks like and what middleware you use.

- `\Graphpinator\Request\JsonRequestFactory` 
  - Simplest form of request - json with keys `query`, `variables` and `operationName`
  - Its constructor argument is `\Graphpinator\Json`
- `\Graphpinator\Request\PsrRequestFactory` 
  - When using Psr compatible middleware
  - The factory extracts all the information from http request itself
  - Its constructor argument is `\Psr\Http\Message\ServerRequestInterface`

In this simple example, we choose the `JsonRequestFactory`.

```
$json = \Graphpinator\Json::fromString(
    '{"query":"query { helloWorld }"}
);
$requestFactory = new \Graphpinator\Request\JsonRequestFactory($json);
$response = $graphpinator->run($requestFactory);
```

This is it, we have our response in `$response` variable. 
It is a  `\JsonSerializable` object of class `\Graphpinator\Response` containing resolved data and errors.

```
echo $response->toString();
```

produces the following

```
{"data":{"helloWorld": "Hello world!"}}
```

### Congratulations

This is the end of the Hello world example, thank you for reading this far.

This example serves as a simple tutorial on how to create a simple type and what must be done for request execution. 
For more information visit [the complete Docs](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/README.md).

## Appendix 1 - Container and Schema using Nette Dependency Injection

Example configuration for Nette framework, which automatically
- finds all types and registers them as services.
- registers `SimpleContainer` and assigns all found types
- registers `Schema`

```
services:
    - Graphpinator\Container\SimpleContainer
    - Graphpinator\Type\Schema(
        @Graphpinator\Container\SimpleContainer,
        @Example\Query,
        null, # Mutation
        null # Subscription
    )
search:
    graphql:
        in: '%appDir%/GraphQL'
        extends:
            - Graphpinator\Type\Contract\NamedDefinition
```

Similiar approach is surely available for other frameworks aswell. 

If you are using some other framework and wish to expand this example, please send a PR.
