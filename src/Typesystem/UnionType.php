<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use Graphpinator\Typesystem\Contract\AbstractType;
use Graphpinator\Typesystem\Contract\NamedTypeVisitor;
use Graphpinator\Typesystem\Contract\Type;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet;
use Graphpinator\Typesystem\Location\UnionLocation;
use Graphpinator\Typesystem\Utils\THasDirectives;
use Graphpinator\Typesystem\Utils\TMetaFields;

abstract class UnionType extends AbstractType
{
    use TMetaFields;
    use THasDirectives;

    public function __construct(
        protected TypeSet $types,
    )
    {
        $this->directiveUsages = new DirectiveUsageSet();
    }

    final public function getTypes() : TypeSet
    {
        return $this->types;
    }

    #[\Override]
    final public function isInstanceOf(Type $type) : bool
    {
        return $type instanceof static;
    }

    #[\Override]
    final public function isImplementedBy(Type $type) : bool
    {
        foreach ($this->types as $temp) {
            if ($temp->isInstanceOf($type->getShapingType())) {
                return true;
            }
        }

        return false;
    }

    #[\Override]
    final public function accept(NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitUnion($this);
    }

    final public function addDirective(
        UnionLocation $directive,
        array $arguments = [],
    ) : static
    {
        $this->directiveUsages[] = new DirectiveUsage($directive, $arguments);

        return $this;
    }
}
