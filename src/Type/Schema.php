<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

final class Schema
{
    use \Nette\SmartObject;
    use \Graphpinator\Utils\TOptionalDescription;

    private \Graphpinator\Type\Container\Container $container;
    private \Graphpinator\Type\Type $query;
    private ?\Graphpinator\Type\Type $mutation;
    private ?\Graphpinator\Type\Type $subscription;

    public function __construct(
        \Graphpinator\Type\Container\Container $typeContainer,
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
            function() : self { return $this; },
        ));
        $this->query->addMetaField(new \Graphpinator\Field\ResolvableField(
            '__type',
            $this->container->introspectionType(),
            function($parent, \Graphpinator\Resolver\ArgumentValueSet $args) : \Graphpinator\Type\Contract\Definition {
                return $this->container->getType($args['name']->getRawValue());
            },
            new \Graphpinator\Argument\ArgumentSet([
                new \Graphpinator\Argument\Argument('name', \Graphpinator\Type\Container\Container::String()->notNull()),
            ]),
        ));
    }

    public function getContainer() : \Graphpinator\Type\Container\Container
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
        $sorter = $sorter ?? new \Graphpinator\Utils\Sort\AlphabeticalSorter();

        $query = $this->query instanceof Type
            ? $this->query->getName()
            : 'null';
        $mutation = $this->mutation instanceof Type
            ? $this->mutation->getName()
            : 'null';
        $subscription = $this->subscription instanceof Type
            ? $this->subscription->getName()
            : 'null';

        $schemaDef = <<<EOL
        schema {
          query: {$query}
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
