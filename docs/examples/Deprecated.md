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

final class Query extends \Graphpinator\Type\Type
{
    protected const NAME = 'Query';
    protected const DESCRIPTION = 'Graphpinator Deprecated: Query type';

    protected function validateNonNullValue($rawValue) : bool
    {
        return true;
    }

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
                'helloWorld',
                \Graphpinator\Container\Container::String()->notNull(),
                function ($parent) : string {
                    return 'Hello world!';
                },
            ),
            (new \Graphpinator\Field\ResolvableField(
                'helloWorldOld',
                \Graphpinator\Container\Container::String()->notNull(),
                function ($parent) : string {
                    return 'Hello world!';
                },
            ))->setDeprecated('Use helloWorld instead.'),
        ]);
    }
}

final class Episode extends \Graphpinator\Type\EnumType
{
    protected const NAME = 'Episode';
    protected const DESCRIPTION = 'Graphpinator Deprecated: Episode enum';
    
    // enum item with description
    public const NEWHOPE = ['NEWHOPE', 'A New Hope']; 
    public const EMPIRE = ['EMPIRE', 'The Empire Strikes Back'];
    
    // enum item without description
    public const JEDI = 'JEDI';
    public const STRIKE = 'STRIKE';

    public function __construct() 
    {
        $items = self::fromConstants();
        $items[self::STRIKE]->setDeprecated('Use EMPIRE instead');

        parent::__construct($items);
    }
}
```

As you can see, declaring fields and enum items as deprecated is really simple - both have setDeprecated() and setDeprecationReason() methods.

> Setting deprecation reason is optional.

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
