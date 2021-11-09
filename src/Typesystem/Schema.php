<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use \Graphpinator\Typesystem\Contract\EntityVisitor;
use \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage;
use \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet;
use \Graphpinator\Typesystem\Exception\RootOperationTypesMustBeDifferent;
use \Graphpinator\Typesystem\Field\ResolvableField;
use \Graphpinator\Typesystem\Location\SchemaLocation;
use \Graphpinator\Typesystem\Utils\TOptionalDescription;

final class Schema implements \Graphpinator\Typesystem\Contract\Entity
{
    use \Nette\SmartObject;
    use TOptionalDescription;
    use \Graphpinator\Typesystem\Utils\THasDirectives;

    public function __construct(
        private Container $container,
        private Type $query,
        private ?Type $mutation = null,
        private ?Type $subscription = null,
    )
    {
        if (\Graphpinator\Graphpinator::$validateSchema) {
            if (self::isSame($query, $mutation) ||
                self::isSame($query, $subscription) ||
                self::isSame($mutation, $subscription)) {
                throw new RootOperationTypesMustBeDifferent();
            }
        }

        $this->directiveUsages = new DirectiveUsageSet();
        $query->addMetaField(ResolvableField::create(
            '__schema',
            $container->getType('__Schema')->notNull(),
            function() : \Graphpinator\Typesystem\Schema {
                return $this;
            },
        ));
        $query->addMetaField(ResolvableField::create(
            '__type',
            $container->getType('__Type'),
            function($parent, string $name) : ?\Graphpinator\Typesystem\Contract\Type {
                return $this->container->getType($name);
            },
        )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
            \Graphpinator\Typesystem\Argument\Argument::create('name', \Graphpinator\Typesystem\Container::String()->notNull()),
        ])));
    }

    public function getContainer() : \Graphpinator\Typesystem\Container
    {
        return $this->container;
    }

    public function getQuery() : Type
    {
        return $this->query;
    }

    public function getMutation() : ?Type
    {
        return $this->mutation;
    }

    public function getSubscription() : ?Type
    {
        return $this->subscription;
    }

    public function accept(EntityVisitor $visitor) : mixed
    {
        return $visitor->visitSchema($this);
    }

    public function addDirective(
        SchemaLocation $directive,
        array $arguments = [],
    ) : self
    {
        $this->directiveUsages[] = new DirectiveUsage($directive, $arguments);

        return $this;
    }

    private static function isSame(?Type $lhs, ?Type $rhs) : bool
    {
        return $lhs === $rhs
            && ($lhs !== null || $rhs !== null);
    }
}
