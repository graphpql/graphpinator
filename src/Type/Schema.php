<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

final class Schema
{
    use \Nette\SmartObject;
    use \Graphpinator\Utils\TOptionalDescription;

    private \Graphpinator\Container\Container $container;
    private \Graphpinator\Type\Type $query;
    private ?\Graphpinator\Type\Type $mutation;
    private ?\Graphpinator\Type\Type $subscription;

    public function __construct(
        \Graphpinator\Container\Container $typeContainer,
        \Graphpinator\Type\Type $query,
        ?\Graphpinator\Type\Type $mutation = null,
        ?\Graphpinator\Type\Type $subscription = null
    )
    {
        $this->container = $typeContainer;
        $this->query = $query;
        $this->mutation = $mutation;
        $this->subscription = $subscription;

        $this->query->addMetaField(new \Graphpinator\Field\ResolvableField(
            '__schema',
            $this->container->introspectionSchema(),
            function() : self {
                return $this;
            },
        ));
        $this->query->addMetaField(\Graphpinator\Field\ResolvableField::create(
                '__type',
                $this->container->introspectionType(),
                function($parent, string $name) : \Graphpinator\Type\Contract\Definition {
                    return $this->container->getType($name);
                },
            )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                new \Graphpinator\Argument\Argument('name', \Graphpinator\Container\Container::String()->notNull()),
            ]))
        );
    }

    public function getContainer() : \Graphpinator\Container\Container
    {
        return $this->container;
    }

    public function getQuery() : \Graphpinator\Type\Type
    {
        return $this->query;
    }

    public function getMutation() : ?\Graphpinator\Type\Type
    {
        return $this->mutation;
    }

    public function getSubscription() : ?\Graphpinator\Type\Type
    {
        return $this->subscription;
    }

    public function printSchema(?\Graphpinator\Utils\Sort\PrintSorter $sorter = null) : string
    {
        $sorter ??= new \Graphpinator\Utils\Sort\AlphabeticalSorter();

        $mutation = $this->mutation instanceof Type
            ? $this->mutation->getName()
            : 'null';
        $subscription = $this->subscription instanceof Type
            ? $this->subscription->getName()
            : 'null';

        $schemaDef = $this->printDescription() . <<<EOL
        schema {
          query: {$this->query->getName()}
          mutation: {$mutation}
          subscription: {$subscription}
        }
        EOL;

        $entries = [$schemaDef];
        $types = $sorter->sortTypes($this->container->getTypes());
        $directives = $sorter->sortDirectives($this->container->getDirectives());

        foreach ($types as $type) {
            $entries[] = $type->printSchema();
        }

        foreach ($directives as $directive) {
            $entries[] = $directive->printSchema();
        }

        return \implode(\PHP_EOL . \PHP_EOL, $entries);
    }
}
