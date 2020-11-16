# Arguments

This example serves as a simple tutorial on how to declare and use arguments on fields.

## Introduction

In this example, we define a simple schema with a few arguments and execute one request on it.
You should be familiar with our previous HelloWorld example to understand the basics.

## Define our types

Here we define one `Query` object type with few arguments on fields.

```php
<?php

declare(strict_types = 1);

namespace Example;

final class Query extends \Graphpinator\Type\Type
{
    protected const NAME = 'Query';
    protected const DESCRIPTION = 'Graphpinator Arguments: Query type';

    protected function validateNonNullValue($rawValue) : bool
    {
        return true;
    }

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'print',
                \Graphpinator\Container\Container::String()->notNull(),
                function ($parent, string $arg) : string {
                    return $arg;
                },
                new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument(
                        'arg',
                        \Graphpinator\Container\Container::String()->notNull(),
                    ),            
                ]),
            ),
            new \Graphpinator\Field\ResolvableField(
                'sum',
                \Graphpinator\Container\Container::Int()->notNull(),
                function ($parent, int $arg1, int $arg2) : int {
                    return $arg1 + $arg2;
                },
                new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument(
                        'arg1',
                        \Graphpinator\Container\Container::Int()->notNull(),
                    ),
                    new \Graphpinator\Argument\Argument(
                        'arg2',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        0
                    ),              
                ]),
            ),
        ]);
    }
}
```

As you can see, declaring arguments is really simple - it is the fourth parameter in `ResolvableField` and third in `Field`, which is used in interfaces.

## Optional step - print schema definition

Visualise our GraphQL schema in type language.

> Declaration of `Container`, `Schema` and `Graphpinator` classes is skipped in this example. Visit our HelloWorld example for more information.

```php
echo $schema->printSchema();
```

produces the following

```graphql
schema {
  query: Query
  mutation: null
  subscription: null
}

"""
Graphpinator Arguments: Query type
"""
type Query {
  print(arg: String!): String!
  sum(arg1: Int!, arg2: Int! = 0): Int!
}
```

## Execute Request

```php
$json = \Graphpinator\Json::fromString(
    '{"query":"query { print(arg: "Hello world!") }"}'
);
$requestFactory = new \Graphpinator\Request\JsonRequestFactory($json);
$response = $graphpinator->run($requestFactory);
```

This is it, we have our response in `$response` variable. The query above will produce:

```json
{"data":{"print": "Hello world!"}}
```

Example using the sum endpoint:

```php
$json = \Graphpinator\Json::fromString(
    '{"query":"query { sum(arg1: 10) }"}'
);
$requestFactory = new \Graphpinator\Request\JsonRequestFactory($json);
$response = $graphpinator->run($requestFactory);
```

will produce

```json
{"data":{"sum": 10}}
```

### Congratulations

This is the end of the Arguments example, thank you for reading this far.
 
- For more information visit [the complete Docs](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/README.md).
- For more examples visit [the examples folder](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/examples).
