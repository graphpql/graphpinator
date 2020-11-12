# Hello World

In this section we define our simple schema and execute it.

## First step - define our types

Schema is mainly defined by types. Each type is separate class extending one of the abstract classes.

- `\Graphpinator\Type\Type`
- `\Graphpinator\Type\UnionType`
- `\Graphpinator\Type\InterfaceType`
- `\Graphpinator\Type\ScalarType`
- `\Graphpinator\Type\EnumType`
- `\Graphpinator\Type\InputType`

In this example we only define one type - the Query type.

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

All types are stored in a `\Graphpinator\Container\Container` class. 
> In this example we are going to assign the type to the `Container` manually, 
but this step can (and is recommended to) be achieved automatically when using some dependency injection.

```
$query = new \Example\Query(); // our Query class which we defined above
$container = new \Graphpinator\Container\SimpleContainer([$query]);
```

## Step three - create Schema

`\Graphpinator\Type\Schema` is a class which brings everything together and is the main class you would be using.
`Schema` needs `Container` of its types, and also an information which types manage different operation types (`query`, `mutation`, `subscription`).
> This step is also skipped when using some dependency injection solution.

```
$schema = new \Graphpinator\Type\Schema($container, $query);
```

## Appendix 1 - Container using Nette Dependency Injection

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

Similiar approach is surely available for other frameworks like Symfony or Laravel. 
If you are using other framework and wish to expand this example, please send a PR.
