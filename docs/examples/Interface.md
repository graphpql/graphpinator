# Interface

This example serves as a simple tutorial on how to create a simple interface and how to resolve the correct concrete type.

## Introduction

In this example, we define a simple schema with one interface and execute one request on it.
You should be familiar with our previous HelloWorld example to understand the basics.

## Define our types

Here we define one interface type - the `HasString` interface - and three object types - `Query`, `TypeA` and `TypeB`.

```php
<?php

declare(strict_types = 1);

namespace Example;

final class Query extends \Graphpinator\Type\Type
{
    protected const NAME = 'Query';
    protected const DESCRIPTION = 'Graphpinator Interface: Query type';

    private \Example\HasString $hasString;
    
    public function __construct(\Example\HasString $hasString) 
    {
        parent::__construct();
    
        $this->hasString = $hasString;
    }

    protected function validateNonNullValue($rawValue) : bool
    {
        return true;
    }

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'interfaceField',
                $this->hasString->notNull(),
                function ($parent) : bool {
                    return (bool) \random_int(0, 1);
                },
            ),
        ]);
    }
}

final class TypeA extends \Graphpinator\Type\Type
{
    protected const NAME = 'TypeA';
    protected const DESCRIPTION = 'Graphpinator Interface: TypeA type';

    public function __construct(\Example\HasString $hasString)
    {
        parent::__construct(new \Graphpinator\Type\InterfaceSet([$hasString]));
    }

    protected function validateNonNullValue($rawValue) : bool
    {
        return \is_int($rawValue);
    }

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'fieldString',
                \Graphpinator\Container\Container::String()->notNull(),
                function (int $parent) : string {
                    return \md5($parent);
                },
            ),
            new \Graphpinator\Field\ResolvableField(
                'fieldInt',
                \Graphpinator\Container\Container::Int()->notNull(),
                function (int $parent) : int {
                    return $parent;
                },
            ),
        ]);
    }
}

final class TypeB extends \Graphpinator\Type\Type
{
    protected const NAME = 'TypeB';
    protected const DESCRIPTION = 'Graphpinator Interface: TypeB type';

    public function __construct(\Example\HasString $hasString)
    {
        parent::__construct(new \Graphpinator\Type\InterfaceSet([$hasString]));
    }

    protected function validateNonNullValue($rawValue) : bool
    {
        return \is_string($rawValue);
    }

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'fieldString',
                \Graphpinator\Container\Container::String()->notNull(),
                function (string $parent) : string {
                    return $parent;
                },
            ),
        ]);
    }
}

final class HasString extends \Graphpinator\Type\InterfaceType
{
    protected const NAME = 'HasString';
    protected const DESCRIPTION = 'Graphpinator Interface: HasString interface';

    private \Example\TypeAccessor $typeAccessor;

    public function __construct(\Example\TypeAccessor $typeAccessor)
    {
        $this->typeAccessor = $typeAccessor;
    }

    public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
    {
        // bool is passed from parent resolve function in Query type
        \assert(\is_bool($rawValue));

        // depending on resolved value, we create either TypeA or TypeB
        return $rawValue
            ? new \Graphpinator\Value\TypeIntermediateValue($this->typeAccessor->getTypeA(), \random_int(0, 100))
            : new \Graphpinator\Value\TypeIntermediateValue($this->typeAccessor->getTypeB(), \md5(\random_int(0, 100)));
    }
    
    protected function getFieldDefinition() : \Graphpinator\Field\FieldSet
    {
        return new \Graphpinator\Field\FieldSet([
            new \Graphpinator\Field\Field(
                'fieldString',
                \Graphpinator\Container\Container::String()->notNull(),
            ),
        };
    }
}

interface TypeAccessor
{
    public function getTypeA() : TypeA;
    public function getTypeB() : TypeB;
}
```

Declaring interface is a little more complicated.
You need an accessor to avoid cyclic constructor dependency.
Fortunately, every dependency injection solution (and you should use one) can be easily configured to create this accessor for you.

> Note that types can implement more interfaces and interfaces can also implement other interfaces.

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
Graphpinator Interface: HasString interface
"""
interface HasString {
  fieldString: String!
}

"""
Graphpinator Interface: Query type
"""
type Query {
  interfaceField: HasString!
}

"""
Graphpinator Interface: TypeA type
"""
type TypeA implements HasString {
  fieldString: String!
  fieldInt: Int!
}

"""
Graphpinator eInterface: TypeB type
"""
type TypeB implements HasString {
  fieldString: String!
}
```

## Execute Request

```php
$json = \Graphpinator\Utils\Json::fromString(
    '{"query":"query { interfaceField { __typename fieldString ... on TypeA { fieldInt } } }"}'
);
$requestFactory = new \Graphpinator\Request\JsonRequestFactory($json);
$response = $graphpinator->run($requestFactory);
```

This is it, we have our response in `$response` variable. Depending on the results of our random resolve functions the result of the query could be something like:

```json
{"data":{"interfaceField": {"__typename": "TypeA", "fieldString": "b53b3a3d6ab90ce0268229151c9bde11", "fieldInt": 55}}}
```

or

```json
{"data":{"interfaceField": {"__typename": "TypeB", "fieldString": "b53b3a3d6ab90ce0268229151c9bde11"}}}
```

### Congratulations

This is the end of the Interface example, thank you for reading this far.
 
- For more information visit [the complete Docs](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/README.md).
- For more examples visit [the examples folder](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/examples).

## Appendix 1 - Accessor using Nette Dependency Injection

Example configuration for Nette framework, which automatically creates accessor needed for example above.

```neon
services:
    - Example\TypeAccessor(
        typeA: @Example\TypeA,
        typeB: @Example\TypeB,
    )
```

A similar approach is surely available for other frameworks as well.

If you are using some other framework and wish to expand this example, please send a PR.
