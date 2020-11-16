# Simple Enum

In this example, we define a simple schema with enum and execute one request on it.
You should be familiar with our previous HelloWorld example to understand the basics.

## Define our types

Here we define one enum type - the `Episode` enum - and one `Query` object type.

```php
<?php

declare(strict_types = 1);

namespace Example;

final class Query extends \Graphpinator\Type\Type
{
    protected const NAME = 'Query';
    protected const DESCRIPTION = 'Graphpinator SimpleUnion: Query type';

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

    protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
    {
        return new \Graphpinator\Field\ResolvableFieldSet([
            new \Graphpinator\Field\ResolvableField(
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

final class Episode extends \Graphpinator\Type\EnumType
{
    protected const NAME = 'Episode';
    protected const DESCRIPTION = 'Graphpinator SimpleEnum: Episode enum';
    
    // enum item with description
    public const NEWHOPE = ['NEWHOPE', 'A New Hope']; 
    public const EMPIRE = ['EMPIRE', 'The Empire Strikes Back'];
    
    // enum item without description
    public const JEDI = 'JEDI';
}
```

As you can see, declaring enum is really simple - enum items are automaticaly generated from PUBLIC constants.

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
Graphpinator SimpleEnum: Episode enum
"""
enum Episode {
  "A New Hope"
  HOPE
  
  "The Empire Strikes Back"
  EMPIRE

  JEDI
}

"""
Graphpinator SimpleEnum: Query type
"""
type Query {
  randomEpisode: Episode!
}
```

## Execute Request

```php
$json = \Graphpinator\Json::fromString(
    '{"query":"query { randomEpisode }"}'
);
$requestFactory = new \Graphpinator\Request\JsonRequestFactory($json);
$response = $graphpinator->run($requestFactory);
```

This is it, we have our response in `$response` variable. Depending on results of our random resolve functions the result of the query could be something like like

```json
{"data":{"randomEpisode": "EMPIRE"}}
```

or

```json
{"data":{"randomEpisode": "JEDI"}}
```

### Congratulations

This is the end of the Simple Enum example, thank you for reading this far.

This example serves as a simple tutorial on how to create a simple enum and its items. 
- For more information visit [the complete Docs](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/README.md).
- For more examples visit [the examples folder](https://github.com/infinityloop-dev/graphpinator/blob/master/docs/examples).
