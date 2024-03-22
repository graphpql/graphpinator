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
  - implements logic to decide which concrete type to be resolved
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
  length(unit: LengthUnit! = METER): Float
}
```

```php
<?php declare(strict_types = 1);

namespace App\Type;

use App\Dto\StarshipDto;
use App\Enum\LengthUnit as LengthUnitEnm;
use App\Type\LengthUnit as LengthUnitType;
use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\Type;

#[Description('My Starship type')]
final class Starship extends Type
{
    protected const NAME = 'Starship'; // This is required
  
    public function __construct(
        private LengthUnitType $lengthUnit,
    )
    {
        parent::__construct();
    }
  
    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return $rawValue instanceof StarshipDto;
    }
    
    protected function getFieldDefinition() : ResolvableFieldSet
    {
        return new ResolvableFieldSet([
            ResolvableField::create(
                'id', 
                Container::ID()->notNull(), 
                function (StarshipDto $parentValue) : string|int {
                    return $startshipDto->id; // or any other resolve function
                },
            ),
            ResolvableField::create(
                'name', 
                Container::String()->notNull(), 
                function (StarshipDto $parentValue) : string {
                    // resolve function
                },
            ),
            ResolvableField::create(
                'length', 
                Container::Float(), 
                function (StarshipDto $parentValue, LengthUnitEnum $unit) : ?float {
                    // resolve function
                },
            )->setArguments(new ArgumentSet([
                Argument::create('unit', $this->lengthUnit->notNull())
                    ->setDefaultValue(LengthUnitEnum::METER),
            ]))
        ]);
    } 
}
```

Fields are defined using `getFieldDefinition` function. This is (apart from secondary performance advantages) done because of a possible cyclic dependency across fields. Fields are therefore loaded lazily using this method, instead of passing FieldSet directly to constructor.

The `validateNonNullValue` function allows the programmer to check if the parent resolver passed a correct value for this type. The argument is any value resolved from parent resolver, except `null` which has a special meaning for the GraphQL. When the function returns `false` an `InvalidValue` is thrown.

#### Implementing interface

In order to make Type implement interface, pass `InterfaceSet` to parent constructor.

```php
public function __construct(
    \App\Type\MyInterface $interfaceType, // descendant of Graphpinator\Typesystem\InterfaceType
{
    parent::__construct(new \Graphpinator\Typesystem\InterfaceSet([$interfaceType]));
}
```

The contract of the interface must be satisfied, the variance rules apply on both argument types and field result types. Validation against interface contract is done right after lazy-loading of fields.

### Interface

> \Graphpinator\Typesystem\InterfaceType

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

namespace App\Type;

use App\Dto\Human as HumanDto;
use App\Dto\Droid as DroidDto;
use App\Type\Episode;
use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Field\Field;
use Graphpinator\Typesystem\Field\FieldSet;
use Graphpinator\Typesystem\InterfaceType;

#[Description('My Character interface')]
final class Character extends InterfaceType
{
    protected const NAME = 'Character';
    
    public function __construct(
        private Episode $episode,
        private CharacterAccessor $characterAccessor,
    )
    {
        parent::__construct();
    }
    
    protected function getFieldDefinition() : FieldSet
    {
        return new FieldSet([
            Field::create(
                'id', 
                Container::ID()->notNull(), 
            ),
            Field::create(
                'name', 
                Container::String()->notNull(), 
            ),
            Field::create(
                'friends', 
                $this->list(), // nullable list with nullable contents
            ),
            Field::create(
                'appearsIn', 
                $this->episode->list()->notNull(),  // not-null list with nullable contents
            ),
        ]);
    }

    public function createResolvedValue(mixed $rawValue) : TypeIntermediateValue
    {
        return match ($rawValue::class) {
            HumanDto::class => new TypeIntermediateValue($this->characterAccessor->getHumanType(), $rawValue),
            DroidDto::class => new TypeIntermediateValue($this->characterAccessor->getDroidType(), $rawValue),
        };
    }
}
```

Fields are defined using `getFieldDefinition` function using the same concept as for defining `Type`. The only difference is the absence of a resolve function, because Interfaces cannot be resolved directly. Field definitions are used to validate contract with Types implementing this interface.

Additionally, the `createResolvedValue` function must be implemented to decide which concrete type the resolved value belongs to. The argument is any value resolved from parent resolver, except `null` which has a special meaning for the GraphQL. The result of this method is a structure of the concrete type and the undelying value which will be passed into it. 

This may cause trouble as the cyclic dependency appears, the contrete types need the interface in order to implement it and the interface need the conrete types in order to resolve the value. This is a common scenario in the GraphQL, as the types reference each other and can resolve in cycles. In this example we worked around it by passing an accessor as a constructor dependency instead of the types directly. The implementation of the accessor depends on which framework and/or DI solution you use.

Interfaces can also implement other interfaces using the same procedure as types - passing `InterfaceSet` into parent constructor.
In this case the fields from parent interface are automatically included and there is no need to repeat the field definitions in the child, unless you wish to be more specific - but keep in mind that covariance/contravariance rules must be applied.

### Union

> \Graphpinator\Typesystem\UnionType

```graphql
union SearchResult = Human | Droid | Starship
```

```php
<?php declare(strict_types = 1);

namespace App\Type;

use App\Dto\Human as HumanDto;
use App\Dto\Droid as DroidDto;
use App\Dto\Starship as StarshipDto;
use App\Type\Human;
use App\Type\Droid;
use App\Type\Starship;
use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\TypeSet;
use Graphpinator\Typesystem\UnionType;
use Graphpinator\Value\TypeIntermediateValue;

#[Description('My SearchResult union')]
final class SearchResult extends UnionType
{
    protected const NAME = 'SearchResult';

    public function __construct(
        private Human $human,
        private Droid $droid,
        private Starship $starship,
    )
    {
        parent::__construct(new TypeSet([$human, $droid, $starship]));
    }

    public function createResolvedValue(mixed $rawValue) : TypeIntermediateValue
    {
        return match ($rawValue::class) {
            HumanDto::class => new TypeIntermediateValue($this->human, $rawValue),
            DroidDto::class => new TypeIntermediateValue($this->droid, $rawValue),
            StarshipDto::class => new TypeIntermediateValue($this->starship, $rawValue),
        };
    }
}
```

In similar fashion as for the `Interface`, the `createResolvedValue` function must be implemented to decide which type the resolved value belongs to.

### Scalar

### Enum

### Input


## Creating schema

In order to execute any query using GraPHPinator, you are expected to create instance of `\Graphpinator\Type\Schema`.
