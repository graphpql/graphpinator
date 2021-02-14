<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

final class Schema implements \Graphpinator\Typesystem\Entity
{
    use \Nette\SmartObject;
    use \Graphpinator\Utils\TOptionalDescription;

    public function __construct(
        private \Graphpinator\Container\Container $container,
        private \Graphpinator\Type\Type $query,
        private ?\Graphpinator\Type\Type $mutation = null,
        private ?\Graphpinator\Type\Type $subscription = null
    )
    {
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
        ])));
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

    public function accept(\Graphpinator\Typesystem\EntityVisitor $visitor) : mixed
    {
        return $visitor->visitSchema($this);
    }
}
