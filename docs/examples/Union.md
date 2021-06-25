# Union

This example serves as a simple tutorial on how to create a simple union and how to resolve the correct concrete type.

## Introduction

In this example, we define a simple schema with one union and execute one request on it.
You should be familiar with our previous HelloWorld example to understand the basics.

## Define our types

Here we define one union type - the `ABUnion` union - and three object types - `Query`, `TypeA` and `TypeB`.

```php
<?php

declare(strict_types = 1);

namespace Example;

final class Query extends \Graphpinator\Typesystem\Type
{
    protected const NAME = 'Query';
    protected const DESCRIPTION = 'Graphpinator Union: Query type';

    private \Example\ABUnion $abUnion;
    
    public function __construct(\Example\ABUnion $abUnion) 
    {
        parent::__construct();
    
        $this->abUnion = $abUnion;
    }

    public function validateNonNullValue($rawValue) : bool
    {
        return true;
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'simpleUnion',
                $this->abUnion->notNull(),
                function ($parent) : bool {
                    return (bool) \random_int(0, 1);
                },
            ),
        ]);
    }
}

final class TypeA extends \Graphpinator\Typesystem\Type
{
    protected const NAME = 'TypeA';
    protected const DESCRIPTION = 'Graphpinator Union: TypeA type';

    public function validateNonNullValue($rawValue) : bool
    {
        return \is_int($rawValue);
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'fieldInt',
                \Graphpinator\Typesystem\Container::Int()->notNull(),
                function (int $parent) : int {
                    return $parent;
                },
            ),
        ]);
    }
}

final class TypeB extends \Graphpinator\Typesystem\Type
{
    protected const NAME = 'TypeB';
    protected const DESCRIPTION = 'Graphpinator Union: TypeB type';

    public function validateNonNullValue($rawValue) : bool
    {
        return \is_string($rawValue);
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'fieldString',
                \Graphpinator\Typesystem\Container::String()->notNull(),
                function (string $parent) : string {
                    return $parent;
                },
            ),
        ]);
    }
}

final class ABUnion extends \Graphpinator\Typesystem\UnionType
{
    protected const NAME = 'ABUnion';
    protected const DESCRIPTION = 'Graphpinator Union: ABUnion union';

    private \Example\TypeA $typeA;
    private \Example\TypeB $typeB;

    public function __construct(
        \Example\TypeA $typeA,
        \Example\TypeB $typeB
    )
    {
        parent::__construct(new \Graphpinator\Typesystem\TypeSet([
            $typeA,
            $typeB,
        ]));

        $this->typeA = $typeA;
        $this->typeB = $typeB;
    }

    public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
    {
        // bool is passed from parent resolve function in Query type
        \assert(\is_bool($rawValue));

        // depending on resolved value, we create either TypeA or TypeB
        return $rawValue
            ? new \Graphpinator\Value\TypeIntermediateValue($this->typeA, \random_int(0, 100))
            : new \Graphpinator\Value\TypeIntermediateValue($this->typeB, \md5(\random_int(0, 100)));
    }
}
```

As you can see, declaring a union is really simple - just create a set of types that are part of the union.

## Optional step - print schema definition

Visualise our GraphQL schema in type language.

> Declaration of `Container`, `Schema` and `Graphpinator` classes is skipped in this example. Visit our HelloWorld example for more information.

Printing the schema using `infinityloop-dev/graphpinator-printer` produces following schema.

```graphql
schema {
  query: Query
  mutation: null
  subscription: null
}

"""
Graphpinator Union: ABUnion union
"""
union ABUnion = TypeA | TypeB

"""
Graphpinator Union: Query type
"""
type Query {
  simpleUnion: ABUnion!
}

"""
Graphpinator Union: TypeA type
"""
type TypeA {
  fieldInt: Int!
}

"""
Graphpinator Union: TypeB type
"""
type TypeB {
  fieldString: String!
}
```

## Execute Request

```php
$json = \Infinityloop\Utils\Json::fromString(
    '{"query":"query { simpleUnion { __typename ... on TypeA { fieldInt } ... on TypeB { fieldString } } }"}'
);
$requestFactory = new \Graphpinator\Request\JsonRequestFactory($json);
$response = $graphpinator->run($requestFactory);
```

This is it, we have our response in `$response` variable. Depending on the results of our random resolve functions the result of the query could be something like:

```json
{"data":{"simpleUnion": {"__typename": "TypeA", "fieldInt": 55}}}
```

or

```json
{"data":{"simpleUnion": {"__typename": "TypeB", "fieldString": "b53b3a3d6ab90ce0268229151c9bde11"}}}
```

### Congratulations

This is the end of the Union example, thank you for reading this far.
 
- For more information visit [the complete Docs](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/README.md).
- For more examples visit [the examples folder](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/examples).
