<?php

declare(strict_types = 1);

namespace Graphpinator\Type;

abstract class UnionType extends \Graphpinator\Type\Contract\AbstractType
{
    use \Graphpinator\Type\Contract\TMetaFields;
    use Graphpinator\Typesystem\Utils\THasDirectives;

    public function __construct(protected \Graphpinator\Type\TypeSet $types)
    {
        $this->directiveUsages = new \Graphpinator\DirectiveUsage\DirectiveUsageSet();
    }

    final public function getTypes() : \Graphpinator\Type\TypeSet
    {
        return $this->types;
    }

    final public function isInstanceOf(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        if ($type instanceof NotNullType) {
            return $this->isInstanceOf($type->getInnerType());
        }

        return $type instanceof static;
    }

    final public function isImplementedBy(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        foreach ($this->types as $temp) {
            if ($temp->isInstanceOf($type)) {
                return true;
            }
        }

        return false;
    }

    final public function accept(\Graphpinator\Typesystem\NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitUnion($this);
    }

    final public function addDirective(
        \Graphpinator\Directive\Contract\UnionLocation $directive,
        array $arguments = [],
    ) : static
    {
        $this->directiveUsages[] = new \Graphpinator\DirectiveUsage\DirectiveUsage($directive, $arguments);

        return $this;
    }
}
