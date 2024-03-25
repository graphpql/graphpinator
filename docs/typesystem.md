# Schema and Typesystem

## Introduction

In GraphQL, the Schema is the heart of your API. It describes the operations your service supports, the data structures it expects as inputs, and the data it returns to the client. This is all achieved using GraphQL's [type system](https://graphql.org/learn/schema/).

> Before diving in, ensure you have a basic understanding of the GraphQL type system.

## Understanding types

This section describes internal architecture of the type system, feel free to skip to next section.

In GraPHPinator, types are descendants of the `\Graphpinator\Typesystem\Contract\Type` interface. They can be categorized into two main groups:

- Modifier types are the well known `NotNullType` and `ListType`
  - descendants of `\Graphpinator\Typesystem\Contract\ModifierType`
  - basicaly decorators around other types declaring nullability or declaring an array
  - cannot work on their own
- Named types are "the real" types which represent the core building blocks of your schema.
  - descendants of `\Graphpinator\Typesystem\Contract\NamedType`
  - can be either abstract or concrete
    - Abstract types are `InterfaceType` and `UnionType`
      - descendants of `\Graphpinator\Typesystem\Contract\AbstractType`
      - implements logic to decide which concrete type to be resolved
    - Concrete types are `Type`, `InputType`, `EnumType` and `ScalarType`
      - descendants of `\Graphpinator\Typesystem\Contract\ConcreteType`

This hierarchy provides a logical grouping for types. Let's jump into how to define each kind!

## Creating types

Here, we'll explore examples for defining each type category in GraPHPinator.

### Type

> \Graphpinator\Typesystem\Type

Here's an example of a Starship type:

```graphql
# My Starship type
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
    protected const NAME = 'Starship'; // required
  
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
                function (StarshipDto $starshipDto) : string|int {
                    return $starshipDto->id; // or any other resolve function
                },
            ),
            ResolvableField::create(
                'name', 
                Container::String()->notNull(), 
                function (StarshipDto $starshipDto) : string {
                    // resolve function
                },
            ),
            ResolvableField::create(
                'length', 
                Container::Float(), 
                function (StarshipDto $starshipDto, LengthUnitEnum $unit) : ?float {
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

Fields are defined using the `getFieldDefinition` function. 
This is done, apart from potential performance benefits, due to an unavoidable cyclic dependency across fields. 
Therefore, fields are loaded lazily using this method instead of passing FieldSet directly to the constructor.

The resolve function always receives at least one parameter - the value from a parent resolver (or null if this is a first-level resolver). 
Additional parameters are passed for each of the field's arguments. 
In the example above, the `length` field has an argument `unit` of the `LengthUnit` enum type, so the resolve function receives an additional parameter `$unit` of the `LengthUnit` native enum type.

> The GraphQL specification allows field arguments and input fields to be omitted and have an empty value (not `null` but unspecified).
> This functionality is deliberately not implemented for field arguments to leverage PHP type safety. It works as expected for input fields.

The `validateNonNullValue` function allows the programmer to check if the parent resolver passed a correct value for this type. 
The argument is any value resolved from the parent resolver, except `null`, which has a special meaning in GraphQL. 
When the function returns `false`, an `InvalidValue` exception is thrown.

#### Implementing interface

To make a `Type` implement an interface, pass an `InterfaceSet` to the parent constructor.

```php
public function __construct(
    \App\Type\MyInterface $interfaceType, // descendant of Graphpinator\Typesystem\InterfaceType
{
    parent::__construct(new \Graphpinator\Typesystem\InterfaceSet([$interfaceType]));
}
```

The contract of the interface must be satisfied; variance rules apply to both argument types and field result types. Validation against the interface contract is done right after lazy-loading of fields.

### Interface

> \Graphpinator\Typesystem\InterfaceType

```graphql
# My Character interface
interface Character {
  id: ID!
  name: String!
  friends: [Character]
  appearsIn: [Episode!]!
}
```
```php
<?php declare(strict_types = 1);

namespace App\Type;

use App\Di\CharacterAccessor;
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
                $this->list(),
            ),
            Field::create(
                'appearsIn', 
                // $this->episode->list(), // nullable list with nullable contents
                // $this->episode->notNull()->list(), // nullable list with not-null contents
                // $this->episode->list()->notNull(), // not-null list with nullable contents
                $this->episode->notNullList(), // not-null list with not-null contents
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

Fields are defined using `getFieldDefinition` function, following the same concept as defining `Type`. 
The difference lies in the absence of a resolve function because interfaces cannot be resolved directly. Field definitions are used to validate the contract with types implementing this interface.

Additionally, the `createResolvedValue` function must be implemented to determine which concrete type the resolved value belongs to. 
The argument is any value resolved from the parent resolver, except `null`, which has a special meaning in GraphQL. 
The result of this method is a structure of the concrete type and the underlying value which will be passed into it.

This may pose a challenge as cyclic dependencies appear; the concrete types need the interface to implement it, and the interface needs the concrete types to resolve the value. 
This is a common scenario in GraphQL, as types reference each other and can result in cycles. In this example, we worked around it by passing an accessor as a constructor dependency instead of the types directly. 
The implementation of the accessor depends on which framework and/or DI solution you use.

Interfaces can also implement other interfaces using the same procedure as types, by passing an `InterfaceSet` into the parent constructor.
In this case, the fields from the parent interface are automatically included, and there is no need to repeat the field definitions in the child unless you wish to be more specific. 
However, keep in mind that covariance/contravariance rules must be applied.

### Union

> \Graphpinator\Typesystem\UnionType

```graphql
# My SearchResult union
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

Similarly to `Interface`, the `createResolvedValue` function must be implemented to determine which type the resolved value belongs to.

### Scalar

> \Graphpinator\Typesystem\ScalarType

```graphql
# EmailAddress type - string which contains valid email address.
scalar EmailAddress @specifiedBy(url: "https://datatracker.ietf.org/doc/html/rfc5322#section-3.4.1")
```
```php
<?php declare(strict_types = 1);

namespace App\Type;

use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\ScalarType;

#[Desctiption('EmailAddress type - string which contains valid email address.')]
final class EmailAddressType extends ScalarType
{
    protected const NAME = 'EmailAddress';

    public function __construct()
    {
        parent::__construct();

        $this->setSpecifiedBy('https://datatracker.ietf.org/doc/html/rfc5322#section-3.4.1');
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return \is_string($rawValue)
            && (bool) \filter_var($rawValue, \FILTER_VALIDATE_EMAIL);
    }
}
```

The `validateNonNullValue` works similarly to that in `Type`. When the function returns `false` an `InvalidValue` exception is thrown. This can be used to restrict the value of this scalar to a valid email address.

This example is taken from the extra-types [package](https://github.com/graphpql/graphpinator-extra-types), which includes some useful types beyond the scope of the official specification.

### Enum

> \Graphpinator\Typesystem\EnumType

```graphql
# My Episode enum
enum Episode {
  NEWHOPE
  EMPIRE
  # <3
  JEDI
}
```
```php
<?php declare(strict_types = 1);

namespace App\Type;

use App\Enum\Episode as EpisodeEnum;
use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\EnumType;

#[Description('My Episode enum')]
final class Episode extends EnumType
{
    protected const string NAME = 'Episode';

    public function __construct()
    {
        parent::__construct(self::fromEnum(EpisodeEnum::class));
    }
}
```
```php
<?php declare(strict_types = 1);

namespace App\Enum;

use Graphpinator\Typesystem\Attribute\Description;

enum Episode : string
{
    case NEWHOPE = 'NEWHOPE';
    case EMPIRE = 'EMPIRE';
    #[Description('<3')]
    case JEDI = 'JEDI';
}
```

The enums are created by extending the `EnumType` and passing and `EnumItemsSet` to the parent constructor. 
While this may seem verbose, it can be easily automated using PHP native enums (backed by string) and the `fromEnum` shortcut function.

The `Description` attribute can also be added to each enum case for additional documentation.

### Input

> \Graphpinator\Typesystem\InputType

```graphql
# My ReviewInput input
input ReviewInput {
  # Required
  stars: Int!
  commentary: String = null
  email: EmailAddress = null
}
```
```php
<?php declare(strict_types = 1);

namespace App\Type;

use App\Dto\ReviewInput;
use App\Type\EmailAddressType;
use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\InputType;

#[Description('My ReviewInput input')]
final class ReviewInput extends InputType
{
    protected const string NAME = 'ReviewInput';
    protected const string DATA_CLASS = ReviewInputDto::class;

    public function __construct(
        private EmailAddressType $emailAddressType,
    )
    {
        parent::__construct();
    }

    protected function getFieldDefinition() : ArgumentSet
    {
        return new ArgumentSet([
            Argument::create('stars', Container::Int()->notNull())
                ->setDescription('Required'),
            Argument::create('commentary', Container::String())
                ->setDefaultValue(null),
            Argument::create('email', $this->emailAddressType)
                ->setDefaultValue(null),
        ]);
    }
}
```
```php
<?php declare(strict_types = 1);

namespace App\Dto;

final class ReviewInputDto
{
    public int $stars;
    public ?string $commentary;
    public ?string $email;
}

```

Input fields are defined using the `getFieldDefinition` function similarly to defining `Type`, but now we create instances of an `Argument`. 
The default value can be set to each argument using a `setDefaultValue` function. 

When an input type is used as a field argument, the `\stdClass` value is provided to the resolver. 
This can be changed using an `DATA_CLASS` constant, where the classname of the desired DTO can be placed. 
The DTO may declare properties with names and types corresponding to the declaration of an input type.

> The properties must be `public` and must not be `readonly` because GraPHPinator hydrates the properties one by one and not by any constructor.

When a value is omitted by the GraphQL request, the value will not be set into the DTO. This has varied consequences depending on the implementation of the DTO:
  - When a `DATA_CLASS` is not overwritten, the ommited property does not exist in the hydrated `\stdClass` instance.
  - When a `DATA_CLASS` is overwritten and the property is not typed, the ommited property exist in the hydrated DTO instance and has a `null` value, as PHP makes `null` the default for properties without a type.
  - When a `DATA_CLASS` is overwritten and the property is typed, the ommited property exist in the hydrated DTO instance with an `unset` value, following PHP's behavior for typed properties.

## Creating schema

A schema serves as the orchestrator of all components within a GraphQL API. It encompasses a registry of recognized types and directives and specifies the root types responsible for handling `query`, `mutation`, and `subscription` requests.

### Root types

In GraPHPinator, root types are standard object types, with the distinction that the parent value is always `null`.

```php
<?php declare(strict_types = 1);

namespace App\Type;

use App\Dto\DroidDto;
use App\Dto\StarshipDto;
use App\Dto\ReviewInputDto;
use App\Query\ThirdField;
use App\Type\ReviewInput;
use App\Type\SearchResult;
use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\Type;

#[Description('My Query type')]
final class Query extends Type
{
    protected const NAME = 'Query';

    public function __construct(
        private SearchResult $searchResult,
        private ReviewInput $reviewInput,
        private ThirdField $thirdField,
        private DatabaseHandler $databaseHandler,
    )
    {
        parent::__construct();
    }

    public function validateNonNullValue(mixed $rawValue) : bool
    {
        return true;
    }

    protected function getFieldDefinition() : ResolvableFieldSet
    {
        return new ResolvableFieldSet([
            ResolvableField::create(
                'search',
                $this->searchResult->notNullList(),
                function (null $parent) : array {
                    // the return type for a list do not have to be an array, any iterable is accepted
                    return [
                        new DroidDto(),
                        new StarshipDto(),
                    ];
                },
            ),

            // this operation is included in the query type for the sake of simplicity, although it should be within a mutation
            ResolvableField::create(
                'postReview',
                Container::Boolean()->notNull(),
                function (null $parent, ReviewInputDto $input) : bool {
                    return $this->databaseHandler->insertReview($input);
                },
            )->setArguments(new ArgumentSet([
                Argument::create('input', $this->reviewInput->notNull()),
            ])),

            // another query field as a service to be more organized
            $this->thirdField,
        ]);
    }
}
```

As the number of `query` operations grows, the lengh of the file can become unwieldy. To enhance organization, it is possible to extend `ResolvableField` and create a separate service for it.

```php
<?php declare(strict_types = 1);

namespace App\Query;

use App\Dto\Starship as StarshipDto;
use App\Type\Starshiup;
use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Field\ResolvableField;

final class ThirdField extends ResolvableField
{
    public function __construct(
        Starship $starship,
        private Dependency $dependency, // dependencies are also injected here and do not pollute the root type
    )
    {
        parent::__construct('thirdField', $starship->notNull(), $this->resolve(...));

        $this->setArguments(new ArgumentSet([
            Argument::create('id', Container::ID()),
        ]));
    }

    private function resolve(null $parent, ?string $id) : StarshipDto
    {
        // my logic here, organized in a specialized class
    }
}
```
This principle is not limited to the root type fields. It is possible to extend `ResolvableField` to create any number of reusable fields.

### Type container

> \Graphpinator\Typesystem\Container

The `Type container` serves as a repository for all known types and directives within a schema.
Each type class must be a singletos and must be registered within the `Type container`.
An included basic implementation, `\Graphpinator\SimpleContainer`, facilitates this by accepting arrays of types and directives as arguments.
However, it's recommended to populate these arrays through a dependency injection (DI) solution.

> Further details regarding DI configuration should be accessible within the adapter package.
> There are currently packages available for [Symfony](https://github.com/graphpql/graphpinator-symfony) and [Nette](https://github.com/graphpql/graphpinator-nette) frameworks.

Scalar types and directives specified by the GraphQL specification are automatically bundled within the `Type container` and should not be registered alongside custom types. 
The abstract `\Graphpinator\Typesystem\Container` provides static shortcuts to allow quick access to built-in types:

 - `Container::Int()`
 - `Container::Float()`
 - `Container::String()`
 - `Container::Boolean()`
 - `Container::ID()`
 - `Container::directiveSkip()`
 - `Container::directiveInclude()`
 - `Container::directiveDeprecated()`
 - `Container::directiveSpecifiedBy()`
 - `Container::directiveOneOf()`

By leveraging these shortcuts, developers can efficiently access the predefined types within their GraphQL schema.

### Schema

> \Graphpinator\Typesystem\Schema

The `Schema` is a simple wrapper around a `Type container` which identifies the root types. This entity is the final step whilst declaring a GraphQL service.
An instance of a `Schema` may be used to execute requests against or render a GraphQL type language documentation of you service.

```graphql
# My StarWars schema
schema {
  query: Query
}
```
```php
<?php declare(strict_types = 1);

namespace App;

use Graphpinator\Typesystem\Schema;

final class StarWarsSchema extends Schema
{
    public function __construct(Container $container)
    {
        parent::__construct($container, $container->getType('Query'));

        $this->setDescription('My StarWars schema');
    }
}
```

In the example above, we created a `Schema` service, which sets the `query` root typ to our `Query` `Type`.
It is not required to create a named class for your `Schema`; you may create a instance of the `Graphpinator\Typesystem\Schema` directly. 

```php
$container = new \Graphpinator\SimpleContainer([new \App\Type\Query(), /* other types */], [/* custom directives */]);
$schema = new \Graphpinator\Typesystem\Schema($container, $container->getType('query'));
```

While it's not mandatory to create a named class for your `Schema`, doing so can make organization easier, especially as your application grows to support multiple schemas.

## Directives

