<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

use Graphpinator\Typesystem\Contract\LeafType;
use Graphpinator\Typesystem\Contract\NamedTypeVisitor;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet;
use Graphpinator\Typesystem\Location\ScalarLocation;
use Graphpinator\Typesystem\Spec\SpecifiedByDirective;
use Graphpinator\Typesystem\Utils\THasDirectives;

abstract class ScalarType extends LeafType
{
    use THasDirectives;

    public function __construct()
    {
        $this->directiveUsages = new DirectiveUsageSet();
    }

    #[\Override]
    final public function accept(NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitScalar($this);
    }

    public function coerceValue(mixed $rawValue) : mixed
    {
        return $rawValue;
    }

    /**
     * @param ScalarLocation $directive
     * @phpcs:ignore
     * @param array<string, mixed> $arguments
     */
    final public function addDirective(
        ScalarLocation $directive,
        array $arguments = [],
    ) : static
    {
        $this->directiveUsages[] = new DirectiveUsage($directive, $arguments);

        return $this;
    }

    public function setSpecifiedBy(string $url) : self
    {
        $this->addDirective(
            Container::directiveSpecifiedBy(),
            ['url' => $url],
        );

        return $this;
    }

    public function getSpecifiedByUrl() : ?string
    {
        foreach ($this->getDirectiveUsages() as $directive) {
            if ($directive->getDirective() instanceof SpecifiedByDirective) {
                return $directive->getArgumentValues()->offsetGet('url')->getValue()->getRawValue();
            }
        }

        return null;
    }
}
