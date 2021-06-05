<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

abstract class UnionType extends \Graphpinator\Typesystem\Contract\AbstractType
{
    use \Graphpinator\Typesystem\Utils\TMetaFields;
    use \Graphpinator\Typesystem\Utils\THasDirectives;

    public function __construct(protected \Graphpinator\Typesystem\TypeSet $types)
    {
        $this->directiveUsages = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet();
    }

    final public function getTypes() : \Graphpinator\Typesystem\TypeSet
    {
        return $this->types;
    }

    final public function isInstanceOf(\Graphpinator\Typesystem\Contract\Type $type) : bool
    {
        if ($type instanceof NotNullType) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return $type instanceof static;
    }

    final public function isImplementedBy(\Graphpinator\Typesystem\Contract\Type $type) : bool
    {
        foreach ($this->types as $temp) {
            if ($temp->isInstanceOf($type)) {
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
