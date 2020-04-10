<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

final class Schema
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Container\Container $typeContainer;
    private \Graphpinator\Type\Type $query;
    private ?\Graphpinator\Type\Type $mutation;
    private ?\Graphpinator\Type\Type $subscription;
    private ?string $description;

    public function __construct(
        \Graphpinator\Type\Container\Container $typeContainer,
        \Graphpinator\Type\Type $query,
        ?\Graphpinator\Type\Type $mutation = null,
        ?\Graphpinator\Type\Type $subscription = null,
        ?string $description = null
    )
    {
        $this->typeContainer = $typeContainer;
        $this->query = $query;
        $this->mutation = $mutation;
        $this->subscription = $subscription;
        $this->description = $description;

        $this->query->addMetaField(new \Graphpinator\Field\ResolvableField(
            '__schema',
            \Graphpinator\Type\Container\Container::introspectionSchema(),
            function() { return $this; },
        ));
        $this->query->addMetaField(new \Graphpinator\Field\ResolvableField(
            '__type',
            \Graphpinator\Type\Container\Container::introspectionType(),
            function($parent, \Graphpinator\Normalizer\ArgumentValueSet $args) : \Graphpinator\Type\Contract\Definition {
                return $this->typeContainer->getType($args['name']->getRawValue());
            },
            new \Graphpinator\Argument\ArgumentSet([
                new \Graphpinator\Argument\Argument('name', \Graphpinator\Type\Container\Container::String()->notNull()),
            ]),
        ));
    }

    public function getTypeContainer() : \Graphpinator\Type\Container\Container
    {
        return $this->typeContainer;
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

    public function getDescription() : ?string
    {
        return $this->description;
    }
}
