<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem;

abstract class ScalarType extends \Graphpinator\Typesystem\Contract\LeafType
{
    use \Graphpinator\Typesystem\Utils\THasDirectives;

    public function __construct()
    {
        $this->directiveUsages = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet();
    }

    final public function accept(\Graphpinator\Typesystem\Contract\NamedTypeVisitor $visitor) : mixed
    {
        return $visitor->visitScalar($this);
    }

    public function coerceValue(mixed $rawValue) : mixed
    {
        return $rawValue;
    }

    final public function addDirective(
        \Graphpinator\Typesystem\Location\ScalarLocation $directive,
        array $arguments = [],
    ) : static
    {
        $this->directiveUsages[] = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage($directive, $arguments);

        return $this;
    }

    public function setSpecifiedBy(string $url) : self
    {
        $this->addDirective(
            \Graphpinator\Typesystem\Container::directiveSpecifiedBy(),
            ['url' => $url],
        );

        return $this;
    }

    public function getSpecifiedByUrl() : ?string
    {
        foreach ($this->getDirectiveUsages() as $directive) {
            if ($directive->getDirective() instanceof \Graphpinator\Typesystem\Spec\SpecifiedByDirective) {
                return $directive->getArgumentValues()->offsetGet('url')->getValue()->getRawValue();
            }
        }

        return null;
    }
}
