<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

final class Schema implements \Graphpinator\Typesystem\Contract\Entity
{
    use \Nette\SmartObject;
    use \Graphpinator\Typesystem\Utils\TOptionalDescription;
    use \Graphpinator\Typesystem\Utils\THasDirectives;

    public function __construct(
        private \Graphpinator\Typesystem\Container $container,
        private \Graphpinator\Typesystem\Type $query,
        private ?\Graphpinator\Typesystem\Type $mutation = null,
        private ?\Graphpinator\Typesystem\Type $subscription = null,
    )
    {
        if (\Graphpinator\Graphpinator::$validateSchema) {
            if (self::isSame($query, $mutation) || self::isSame($query, $subscription) || self::isSame($mutation, $subscription)) {
                throw new \Graphpinator\Typesystem\Exception\RootOperationTypesMustBeDifferent();
            }
        }

        $this->directiveUsages = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet();
    }

    public function getContainer() : \Graphpinator\Typesystem\Container
    {
        return $this->container;
    }

    public function getQuery() : \Graphpinator\Typesystem\Type
    {
        return $this->query;
    }

    public function getMutation() : ?\Graphpinator\Typesystem\Type
    {
        return $this->mutation;
    }

    public function getSubscription() : ?\Graphpinator\Typesystem\Type
    {
        return $this->subscription;
    }

    public function accept(\Graphpinator\Typesystem\Contract\EntityVisitor $visitor) : mixed
    {
        return $visitor->visitSchema($this);
    }

    public function addDirective(
        \Graphpinator\Typesystem\Location\SchemaLocation $directive,
        array $arguments = [],
    ) : self
    {
        $this->directiveUsages[] = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage($directive, $arguments);

        return $this;
    }

    private static function isSame(?\Graphpinator\Typesystem\Type $lhs, ?\Graphpinator\Typesystem\Type $rhs) : bool
    {
        return $lhs === $rhs
            && ($lhs !== null || $rhs !== null);
    }
}
