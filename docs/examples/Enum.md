# Enum

This example serves as a simple tutorial on how to create a simple enum and its items.

## Introduction

In this example, we define a simple schema with one enum and execute one request on it.
You should be familiar with our previous HelloWorld example to understand the basics.

## Define our types

Here we define one enum type - the `Episode` enum - and one `Query` object type.

```php
<?php

declare(strict_types = 1);

namespace Example;

final class Query extends \Graphpinator\Typesystem\Type
{
    protected const NAME = 'Query';
    protected const DESCRIPTION = 'Graphpinator Union: Query type';

    private \Example\Episode $episode;
    
    public function __construct(\Example\Episode $episode) 
    {
        parent::__construct();
    
        $this->episode = $episode;
    }

    protected function validateNonNullValue($rawValue) : bool
    {
        return true;
    }

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
            new \Graphpinator\Typesystem\Field\ResolvableField(
                'randomEpisode',
                $this->episode->notNull(),
                function ($parent) : string {
                    $enumItemSet = \Example\Episode::fromConstants();
                    $index = \random_int(1, $enumItemSet->count());
                    $i = 1;

                    foreach ($enumItemSet as $enumItem) {
                        if ($i === $index) {
                            return $enumItem->getName();
                        }

                        $i++;
                    };
                },
            ),
        ]);
    }
}

final class Episode extends \Graphpinator\Typesystem\EnumType
{
    protected const NAME = 'Episode';
    protected const DESCRIPTION = 'Graphpinator Enum: Episode enum';
    
    // enum item with description
    
    /** A New Hope */
    public const NEWHOPE = 'NEWHOPE';
    /** The Empire Strikes Back */
    public const EMPIRE = 'EMPIRE';
    
    // enum item without description
    public const JEDI = 'JEDI';

    public function __construct() 
    {
        parent::__construct(self::fromConstants());
    }
}
```

As you can see, declaring enum is really simple - enum items are automatically generated from PUBLIC constants.

## Optional step - print schema definition

Visualize our GraphQL schema in type language.

> Declaration of `Container`, `Schema` and `Graphpinator` classes is skipped in this example. Visit our HelloWorld example for more information.

Printing the schema using infinityloop-dev/graphpinator-printer produces following schema.

```graphql
schema {
  query: Query
  mutation: null
  subscription: null
}

"""
Graphpinator Enum: Episode enum
"""
enum Episode {
  "A New Hope"
  HOPE
  
  "The Empire Strikes Back"
  EMPIRE

  JEDI
}

"""
Graphpinator Enum: Query type
"""
type Query {
  randomEpisode: Episode!
}
```

## Execute Request

```php
$json = \Infinityloop\Utils\Json::fromString(
    '{"query":"query { randomEpisode }"}'
);
$requestFactory = new \Graphpinator\Request\JsonRequestFactory($json);
$response = $graphpinator->run($requestFactory);
```

This is it, we have our response in `$response` variable. Depending on the results of our random resolve functions the result of the query could be something like:

```json
{"data":{"randomEpisode": "EMPIRE"}}
```

or

```json
{"data":{"randomEpisode": "JEDI"}}
```

### Congratulations

This is the end of the Enum example, thank you for reading this far.
 
- For more information visit [the complete Docs](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/README.md).
- For more examples visit [the examples folder](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/examples).
