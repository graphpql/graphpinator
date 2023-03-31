<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

abstract class UnionType extends \Graphpinator\Typesystem\Contract\AbstractType
{
    use \Graphpinator\Typesystem\Utils\TMetaFields;
    use \Graphpinator\Typesystem\Utils\THasDirectives;

    public function __construct(
        protected TypeSet $types,
    )
    {
        $this->directiveUsages = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet();
    }

    final public function getTypes() : TypeSet
    {
        return $this->types;
    }

    final public function isInstanceOf(\Graphpinator\Typesystem\Contract\Type $type) : bool
    {
        return $type instanceof static;
    }

    final public function isImplementedBy(\Graphpinator\Typesystem\Contract\Type $type) : bool
    {
        foreach ($this->types as $temp) {
            if ($temp->isInstanceOf($type->getShapingType())) {
                return true;
            }
        }

        return false;
    }

    final public function accept(\Graphpinator\Typesystem\Contract\NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitUnion($this);
    }

    final public function addDirective(
        \Graphpinator\Typesystem\Location\UnionLocation $directive,
        array $arguments = [],
    ) : static
    {
        $this->directiveUsages[] = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage($directive, $arguments);

        return $this;
    }
}
