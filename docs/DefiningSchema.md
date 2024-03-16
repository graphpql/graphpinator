# Defining Schema

## Introduction

Alpha & Omega in GraphQL type language is a Schema. Schema describes which operations your service supports, which data are expected as inputs and which is your service going to return back to client. All this is done using GraphQL [type system](https://graphql.org/learn/schema/).

> Before reading further, make sure to have basic understanding of the GraphQL [type system](https://graphql.org/learn/schema/).

## Understanding types

This section describes internal architecture of the type system, feel free to skip to next section.

### Modifer & Named types

For GraPHPinator, types are descendants of `\Graphpinator\Typesystem\Contract\Type`.

Types can either be Named or Modifiers. 
- Modifier types are the well known `NotNullType` and `ListType`
  - descendants of `\Graphpinator\Typesystem\Contract\ModifierType`
  - basicaly decorators around other types declaring nullability or declaring an array
  - cannot work on their own
- Named types are "the real" types.
  - descendants of `\Graphpinator\Typesystem\Contract\NamedType`

### Named types

Named types can either be Abstract or Concrete.
- Abstract types are `InterfaceType` and `UnionType`
  - descendants of `\Graphpinator\Typesystem\Contract\AbstractType`
  - implements logic to decide which concrete type it resolved to
- Concrete types are `Type`, `InputType`, `EnumType` and `ScalarType`
  - descendants of `\Graphpinator\Typesystem\Contract\ConcreteType`

This hierarchy describes "logical" grouping of the types, lets jump stright into definitions for each kind.

## Defining types

Lets jump stright into examples for each kind.

### Type

> \Graphpinator\Typesystem\Type

```graphql
type Starship {
  id: ID!
  name: String!
  length(unit: LengthUnit = METER): Float
}
```

```php
<?php declare(strict_types = 1);

use Graphpinator\Normalizer\ArgumentValueSet;
use Graphpinator\Field\ResolvableField;
use Graphpinator\Type\Container\Container;

class Starship extends \Graphpinator\Type\Type
{
  protected const NAME = 'Starship';
  
  private \Graphpinator\Type\EnumType $lengthUnit;
  
  public function __construct(\Graphpinator\Type\EnumType $lengthUnit)
  {
    $this->lengthUnit = $lengthUnit;
  }
  
  protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
  {
      return new \Graphpinator\Field\ResolvableFieldSet([
          new ResolvableField(
            'id', 
            Container::ID()->notNull(), 
            function ($parentValue, ArgumentValueSet $arguments) {
              // resolve function
            },
          ),
          new ResolvableField(
            'name', 
            Container::String()->notNull(), 
            function ($parentValue, ArgumentValuetSet $arguments) {
              // resolve function
            },
          ),
          new ResolvableField(
            'length', 
            Container::Float(), 
            function ($parentValue, ArgumentValueSet $arguments) {
              // resolve function
            },
            new \Graphpinator\Argument\ArgumentSet([
              new \Graphpinator\Argument\Argument('unit', $this->lengthUnitType, 'METER'),
            ])
          ),
      ]);
  } 
}
```

Fields are defined using `getFieldDefinition` function. This is (apart from secondary performance advantages) done because of a possible cyclic dependency across fields. Fields are therefore loaded lazily using this method, instead of passing FieldSet directly to constructor.

Types of fields or input types for arguments are instances of desired type.

#### Implementing interface

In order to make Type implement interface, pass `InterfaceSet` to parent constructor.

```php
public function __construct(\Graphpinator\Type\InterfaceType $interfaceType)
{
    parent::__construct(new \Graphpinator\Utils\InterfaceSet([$interfaceType]));
}
```

Validation against interface contract is done right after lazy-loading of fields.

### Interface

> \Graphpinator\Type\InterfaceType

```graphql
interface Character {
  id: ID!
  name: String!
  friends: [Character]
  appearsIn: [Episode]!
}
```

```php
<?php declare(strict_types = 1);

use Graphpinator\Field\Field;
use Graphpinator\Type\Container\Container;

class Character extends \Graphpinator\Type\InterfaceType
{
  protected const NAME = 'Character';
  
  private \Graphpinator\Type\EnumType $episode;
  private \Graphpinator\Type\Type $character;
  
  public function __construct(\Graphpinator\Type\EnumType $episode, \Graphpinator\Type\Type $character)
  {
    $this->episode = $episode;
    $this->character = $character;
  }
  
  protected function getFieldDefinition() : \Graphpinator\Field\FieldSet
  {
      return new \Graphpinator\Field\FieldSet([
          new Field(
            'id', 
            Container::ID()->notNull(), 
          ),
          new Field(
            'name', 
            Container::String()->notNull(), 
          ),
          new Field(
            'friends', 
            $this->character->list(),
          ),
          new Field(
            'appearsIn', 
            $this->episode->list()->notNull(),
          ),
      ]);
  } 
}
```

Fields are defined using `getFieldDefinition` function using the same concept as for defining Types. The only difference is absence of resolve function, because Interfaces cannot be resolved directly. Field definitions are used to validate contract with Types implementing this interface.

Interfaces can also implement other interfaces using the same procedure as types - passing `InterfaceSet` into parent constructor.
In this case the fields from parent interface are automatically included and there is no need to repeat the field definitions in the child, unless you wish to be more specific - but keep in mind that covariance/contravariance rules must be applied.

### Union

> \Graphpinator\Type\UnionType

```graphql
union SearchResult = Human | Droid | Starship
```

```php
class SearchResult extends \Graphpinator\Type\UnionType
{
    protected const NAME = 'SearchResult';

    public function __construct(\Graphpinator\Type\Type $human, \Graphpinator\Type\Type $droid, \Graphpinator\Type\Type $starship)
    {
        parent::__construct(new \Graphpinator\Utils\ConcreteSet([$human, $droid, $starship]));
    }
}
```

### Scalar

### Enum

### Input


## Creating schema

In order to execute any query using GraPHPinator, you are expected to create instance of `\Graphpinator\Type\Schema`.
