# Constraints

This example serves as a simple tutorial on how to declare and use constraints on fields, arguments and objects.

## Introduction

In this example, we define a simple schema with a few constraints - custom directives that apply value constraints to fields, arguments, and types.
You should be familiar with our previous HelloWorld example to understand the basics.

## Define our types

Here we define one `Query` object type with some leaf constraints and one `AOrB` object type with object constraint.
In this specific example, we assign 
- StringConstraint to the `fieldConstraint` field, which specifies that returned string is at least 5 characters long,
- IntConstraint to the `arg` argument on `argumentConstraint` field, which specifies that inputed int must be >= 10,
- ObjectConstraint to the `AOrB` type, which specifies that either fieldA or fieldB contains a value.


```php
<?php

declare(strict_types = 1);

namespace Example;

final class Query extends \Graphpinator\Type\Type
{
    protected const NAME = 'Query';
    protected const DESCRIPTION = 'Graphpinator Constraints: Query type';

    protected function validateNonNullValue($rawValue) : bool
    {
        return true;
    }

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            (new \Graphpinator\Field\ResolvableField(
                'fieldConstraint',
                \Graphpinator\Container\Container::String()->notNull(),
                function ($parent) : string {
                    return 'Hello world';
                },
            ))->addConstraint(new \Graphpinator\Constraint\StringConstraint(5)),
            new \Graphpinator\Field\ResolvableField(
                'argumentConstraint',
                \Graphpinator\Container\Container::Int()->notNull(),
                function ($parent, int $arg) : int {
                    return $arg;
                },
                new \Graphpinator\Argument\ArgumentSet([
                    (new \Graphpinator\Argument\Argument(
                        'arg',
                        \Graphpinator\Container\Container::Int()->notNull(),
                    ))->addConstraint(new \Graphpinator\Constraint\IntConstraint(10)),            
                ]),
            ),
        ]);
    }
}

final class AOrB extends \Graphpinator\Type\Type
{
    protected const NAME = 'AOrB';
    protected const DESCRIPTION = 'Graphpinator Constraints: AOrB type';

    protected function validateNonNullValue($rawValue) : bool
    {
        return \is_int($rawValue) && \in_array($rawValue, [0, 1], true);
    }

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'fieldA',
                \Graphpinator\Container\Container::Int(),
                function (?int $parent) : ?int {
                    return $parent === 1 ? 1 : null;
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'fieldB',
                \Graphpinator\Container\Container::Int(),
                function (int $parent) : ?int {
                    return $parent === 0 ? 1 : null;
                },
            ),
        ]);
    }
}
```

As you can see, declaring constraints is really simple - call `addConstraint` method on `Argument`, `Field`, `Type`, `InterfaceType` or `InputType` to add constraint.
Make sure to select Constraint corresponding to its usage eg. 
- StringConstraint for String fields/arguments
- ObjectConstraint for types/interfaces/inputs

## Check schema definition

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
Graphpinator Constraints: AOrB type
"""
type AOrB @objectConstraint(exactlyOne: ["fieldA", "fieldB"]) {
  fieldA: Int
  fieldB: Int
}

"""
Graphpinator Constraints: Query type
"""
type Query {
  fieldConstraint: String! @stringConstraint(minLength: 5)
  argumentConstraint(arg: Int! @intConstraint(min: 10)): Int!
}
```

### Congratulations

This is the end of the Constraints example, thank you for reading this far.
 
- For more information visit [the complete Docs](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/README.md).
- For more examples visit [the examples folder](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/examples).
