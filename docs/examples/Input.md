# Input

This example serves as a simple tutorial on how to create a simple input and use it as argument type.

## Introduction

In this example, we define a simple schema with one input and execute one request on it.
You should be familiar with our previous HelloWorld example to understand the basics.

## Define our types

Here we define one `Query` object type and one `Person` input.

```php
<?php

declare(strict_types = 1);

namespace Example;

final class Query extends \Graphpinator\Type\Type
{
    protected const NAME = 'Query';
    protected const DESCRIPTION = 'Graphpinator Input: Query type';

    private \Example\Person $person;

    public function __construct(\Example\Person $person) 
    {
        parent::__construct();
    
        $this->person = $person;
    }

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
                function ($parent, \stdClass $arg) : string {
                    return 'User ' . $arg->name . ', age: ' . $arg->age;
                },
                new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument(
                        'arg',
                        $this->person->notNull(),
                    ),            
                ]),
            ),
        ]);
    }
}

final class Person extends \Graphpinator\Type\InputType
{
    protected const NAME = 'Person';
    protected const DESCRIPTION = 'Graphpinator Input: Person input';

    protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            new \Graphpinator\Argument\Argument(
                'name',
                \Graphpinator\Container\Container::String()->notNull(),
            ),
            new \Graphpinator\Argument\Argument(
                'age',
                \Graphpinator\Container\Container::Int()->notNull(),
            ),
        ]);
    }
}
```

As you can see, declaring an input is really simple - just implement the `getFieldDefinition()` method and return its input fields.

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
Graphpinator Input: Person input
"""
input Person {
  name: String!
  age: Int!
}

"""
Graphpinator Input: Query type
"""
type Query {
  print(arg: Person!): String!
}
```

## Execute Request

```php
$json = \Graphpinator\Json::fromString(
    '{"query":"query { print(arg: {name: "peldax", age: 26}) }"}'
);
$requestFactory = new \Graphpinator\Request\JsonRequestFactory($json);
$response = $graphpinator->run($requestFactory);
```

This is it, we have our response in `$response` variable. The query above will produce:

```json
{"data":{"print": "User peldax, age 26"}}
```

### Congratulations

This is the end of the Input example, thank you for reading this far.
 
- For more information visit [the complete Docs](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/README.md).
- For more examples visit [the examples folder](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/examples).
