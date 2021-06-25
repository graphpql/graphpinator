# Deprecated

This example serves as a simple tutorial on how to declare fields and enum items as deprecated.

## Introduction

In this example, we define a simple schema where we mark some fields and enum items as deprecated.
You should be familiar with our previous HelloWorld example to understand the basics.

## Define our types

Here we define one `Query` object type with deprecated field and one `Episode` enum type with deprecated item.

```php
<?php

declare(strict_types = 1);

namespace Example;

final class Query extends \Graphpinator\Typesystem\Type
{
    protected const NAME = 'Query';
    protected const DESCRIPTION = 'Graphpinator Deprecated: Query type';

    protected function validateNonNullValue($rawValue) : bool
    {
        return true;
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
            \Graphpinator\Typesystem\Field\ResolvableField::create(
                'helloWorld',
                \Graphpinator\Typesystem\Container::String()->notNull(),
                function ($parent) : string {
                    return 'Hello world!';
                },
            ),
            \Graphpinator\Typesystem\Field\ResolvableField::create(
                'helloWorldOld',
                \Graphpinator\Typesystem\Container::String()->notNull(),
                function ($parent) : string {
                    return 'Hello world!';
                },
            )->setDeprecated('Use helloWorld instead.'),
        ]);
    }
}

final class Episode extends \Graphpinator\Typesystem\EnumType
{
    protected const NAME = 'Episode';
    protected const DESCRIPTION = 'Graphpinator Deprecated: Episode enum';
    
    // enum item with description

    /** A New Hope */
    public const NEWHOPE = 'NEWHOPE';
    /** The Empire Strikes Back */
    public const EMPIRE = 'EMPIRE';
    
    // enum item without description
    public const JEDI = 'JEDI';
    public const STRIKE = 'STRIKE';

    public function __construct() 
    {
        $items = self::fromConstants();
        $items[self::STRIKE]->setDeprecated('Use EMPIRE instead.');

        parent::__construct($items);
    }
}
```

As you can see, declaring fields and enum items as deprecated is really simple - both have setDeprecated() method to mark the object as deprecated.
The `setDeprecated` method is a shorcut method to append the Deprecated directive.

> Setting deprecation reason is optional.

## Check schema definition

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
Graphpinator Deprecated: Episode enum
"""
enum Episode {
  "A New Hope"
  HOPE
  
  "The Empire Strikes Back"
  EMPIRE

  JEDI
  STRIKE @deprecated(reason: "Use EMPIRE instead.")
}

"""
Graphpinator Deprecated: Query type
"""
type Query {
  helloWorld: String!
  helloWorldOld: String! @deprecated(reason: "Use helloWorld instead.")
}
```

Deprecated directives are successfully set. Users are encouraged to use new fields/items.

### Congratulations

This is the end of the Deprecated example, thank you for reading this far.
 
- For more information visit [the complete Docs](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/README.md).
- For more examples visit [the examples folder](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/examples).
