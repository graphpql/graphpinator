<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use Graphpinator\Typesystem\Contract\AbstractType;
use Graphpinator\Typesystem\Contract\NamedTypeVisitor;
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
    final public function accept(NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitUnion($this);
    }

    /**
     * @param UnionLocation $directive
     * @phpcs:ignore
     * @param array<string, mixed> $arguments
     */
    final public function addDirective(
        UnionLocation $directive,
        array $arguments = [],
    ) : static
    {
        $this->directiveUsages[] = new DirectiveUsage($directive, $arguments);

        return $this;
    }
}
