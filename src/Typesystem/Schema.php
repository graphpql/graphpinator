<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Contract\Entity;
use Graphpinator\Typesystem\Contract\EntityVisitor;
use Graphpinator\Typesystem\Contract\Type as TypeContract;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Location\SchemaLocation;
use Graphpinator\Typesystem\Utils\THasDirectives;
use Graphpinator\Typesystem\Utils\TOptionalDescription;

class Schema implements Entity
{
    use TOptionalDescription;
    use THasDirectives;

    public function __construct(
        private Container $container,
        private Type $query,
        private ?Type $mutation = null,
        private ?Type $subscription = null,
    )
    {
        $this->directiveUsages = new DirectiveUsageSet();
        $query->addMetaField(ResolvableField::create(
            '__schema',
            $container->getType('__Schema')->notNull(),
            function() : Schema {
                return $this;
            },
        ));
        $query->addMetaField(ResolvableField::create(
            '__type',
            $container->getType('__Type'),
            function($parent, string $name) : ?TypeContract {
                return $this->container->getType($name);
            },
        )->setArguments(new ArgumentSet([
            Argument::create('name', Container::String()->notNull()),
        ])));
    }

    final public function getContainer() : Container
    {
        return $this->container;
    }

    final public function getQuery() : Type
    {
        return $this->query;
    }

    final public function getMutation() : ?Type
    {
        return $this->mutation;
    }

    final public function getSubscription() : ?Type
    {
        return $this->subscription;
    }

    #[\Override]
    final public function accept(EntityVisitor $visitor) : mixed
    {
        return $visitor->visitSchema($this);
    }

    /**
     * @param SchemaLocation $directive
     * @phpcs:ignore
     * @param array<string, mixed> $arguments
     */
    final public function addDirective(SchemaLocation $directive, array $arguments = []) : self
    {
        $this->directiveUsages[] = new DirectiveUsage($directive, $arguments);

        return $this;
    }
}
